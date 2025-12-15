<?php
/**
 * ABIS - Aplikasi Desa Digital
 * Main Entry Point
 */

// Include configuration
require_once 'config/config.php';

// Initialize application
initApp();

// Simple Router Class
class Router {
    private $routes = [];

    /**
     * Add a route
     * @param string $method
     * @param string $path
     * @param callable $handler
     */
    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    /**
     * Get route parameters
     * @param string $path
     * @param string $routePath
     * @return array|null
     */
    private function getParams($path, $routePath) {
        $pathParts = explode('/', trim($path, '/'));
        $routeParts = explode('/', trim($routePath, '/'));

        if (count($pathParts) !== count($routeParts)) {
            return null;
        }

        $params = [];
        for ($i = 0; $i < count($routeParts); $i++) {
            if (strpos($routeParts[$i], ':') === 0) {
                $paramName = substr($routeParts[$i], 1);
                $params[$paramName] = $pathParts[$i];
            } elseif ($routeParts[$i] !== $pathParts[$i]) {
                return null;
            }
        }

        return $params;
    }

    /**
     * Dispatch the request
     * @param string $method
     * @param string $path
     */
    public function dispatch($method, $path) {
        $method = strtoupper($method);
        $path = parse_url($path, PHP_URL_PATH);
        $path = $path ? rtrim($path, '/') : '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $params = $this->getParams($path, $route['path']);
                if ($params !== null) {
                    call_user_func($route['handler'], $params);
                    return;
                }
            }
        }

        // Route not found
        $this->handle404();
    }

    /**
     * Handle 404 errors
     */
    private function handle404() {
        http_response_code(404);
        if (file_exists(VIEWS_DIR . '/errors/404.php')) {
            require VIEWS_DIR . '/errors/404.php';
        } else {
            echo '<h1>404 - Page Not Found</h1>';
            echo '<p>The page you are looking for does not exist.</p>';
        }
    }
}

// Initialize router
$router = new Router();

// Load controllers
require_once CONTROLLERS_DIR . '/HomeController.php';
require_once CONTROLLERS_DIR . '/AuthController.php';
require_once CONTROLLERS_DIR . '/UserController.php';
require_once CONTROLLERS_DIR . '/AdminController.php';

// Create controller instances
$homeController = new HomeController();
$authController = new AuthController();
$userController = new UserController();
$adminController = new AdminController();

// Home route
$router->add('GET', '/', function() {
    global $homeController;
    $homeController->index();
});

$router->add('GET', '/login', function() {
    global $authController;
    $authController->login();
});

$router->add('POST', '/login', function() {
    global $authController;
    $authController->processLogin();
});

$router->add('GET', '/register', function() {
    global $authController;
    $authController->register();
});

$router->add('POST', '/register', function() {
    global $authController;
    $authController->processRegister();
});

$router->add('GET', '/logout', function() {
    global $authController;
    $authController->logout();
});

$router->add('POST', '/logout', function() {
    global $authController;
    $authController->logout();
});

// User routes
$router->add('GET', '/dashboard', function() {
    global $userController;
    requireAuth('user');
    $userController->dashboard();
});

$router->add('GET', '/requests', function() {
    global $userController;
    requireAuth('user');
    $userController->requests();
});

$router->add('GET', '/requests/create', function() {
    global $userController;
    requireAuth('user');
    $userController->createRequest();
});

$router->add('POST', '/requests/create', function() {
    global $userController;
    requireAuth('user');
    $userController->processCreateRequest();
});

$router->add('GET', '/requests/:id', function($params) {
    global $userController;
    requireAuth('user');
    $userController->viewRequest($params['id']);
});

$router->add('GET', '/requests/:id/download', function($params) {
    global $userController;
    requireAuth('user');
    $userController->downloadRequest($params['id']);
});

// Admin routes
$router->add('GET', '/admin/dashboard', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->dashboard();
});

$router->add('GET', '/admin/users', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->users();
});

$router->add('POST', '/admin/users/:id/reset-password', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->resetPassword($params['id']);
});

$router->add('POST', '/admin/users/:id/delete', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->deleteUser($params['id']);
});

$router->add('GET', '/admin/export', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->export();
});

$router->add('GET', '/admin/requests', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->requests();
});

$router->add('POST', '/admin/requests/create', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->createSuratPengantar();
});

$router->add('POST', '/admin/requests/:id/delete', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->deleteRequest($params['id']);
});

$router->add('POST', '/api/admin/requests/:id/status', function($params) {
    global $adminController;
    requireAuth('admin');
    header('Content-Type: application/json');
    $requestId = $params['id'];
    $status = $_POST['status'] ?? '';

    if ($status === 'approved') {
        $success = $adminController->approveRequest($requestId);
        echo json_encode(['success' => $success, 'message' => $success ? 'Request approved successfully' : 'Failed to approve request']);
    } elseif ($status === 'rejected') {
        $success = $adminController->rejectRequest($requestId);
        echo json_encode(['success' => $success, 'message' => $success ? 'Request rejected successfully' : 'Failed to reject request']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
    }
});

$router->add('GET', '/admin/requests', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->requests();
});

$router->add('POST', '/admin/requests/:id/approve', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->approveRequest($params['id']);
});

$router->add('POST', '/admin/requests/:id/reject', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->rejectRequest($params['id']);
});

$router->add('GET', '/admin/requests/:id/download', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->downloadRequest($params['id']);
});

$router->add('GET', '/admin/users', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->users();
});

$router->add('POST', '/admin/users/:id/delete', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->deleteUser($params['id']);
});

$router->add('GET', '/admin/requests', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->requests();
});

$router->add('POST', '/admin/requests/:id/approve', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->approveRequest($params['id']);
});

$router->add('POST', '/admin/requests/:id/reject', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->rejectRequest($params['id']);
});

$router->add('GET', '/admin/requests/:id/download', function($params) {
    global $adminController;
    requireAuth('admin');
    $adminController->downloadRequest($params['id']);
});

// API routes for AJAX requests
$router->add('GET', '/api/letter-types', function() {
    requireAuth();
    header('Content-Type: application/json');
    $letterTypes = fetchAll("SELECT * FROM letter_types WHERE is_active = 1 ORDER BY name");
    echo json_encode($letterTypes);
});

// Get current request method and URI
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Enhanced URI parsing for different web servers (Apache, Nginx, etc.)
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$pathInfo = $_SERVER['PATH_INFO'] ?? '';
$queryString = $_SERVER['QUERY_STRING'] ?? '';

// Handle different server configurations
if (!empty($pathInfo)) {
    // For servers that set PATH_INFO (some CGI setups)
    $requestUri = $pathInfo;
} elseif (strpos($requestUri, $scriptName) === 0) {
    // Remove script name from URI
    $requestUri = substr($requestUri, strlen($scriptName));
} else {
    // For Nginx and other servers, extract path from REQUEST_URI
    $parsedUrl = parse_url($requestUri);
    $requestUri = $parsedUrl['path'] ?? '/';

    // Remove base path if exists (for subdirectory installations)
    $basePath = dirname($scriptName);
    if ($basePath !== '/' && strpos($requestUri, $basePath) === 0) {
        $requestUri = substr($requestUri, strlen($basePath));
    }
}

// Remove query string from URI
if (strpos($requestUri, '?') !== false) {
    $requestUri = substr($requestUri, 0, strpos($requestUri, '?'));
}

// Ensure we have a leading slash and no trailing slash except for root
$requestUri = trim($requestUri, '/');
if (empty($requestUri)) {
    $requestUri = '/';
} else {
    $requestUri = '/' . $requestUri;
}

// Debug logging (uncomment for troubleshooting)
// error_log("Final Request URI: $requestUri");
// error_log("Original REQUEST_URI: " . $_SERVER['REQUEST_URI']);
// error_log("Script Name: $scriptName");
// error_log("Path Info: $pathInfo");

// Dispatch the request
try {
    $router->dispatch($requestMethod, $requestUri);
} catch (Exception $e) {
    // If routing fails, show 404
    http_response_code(404);
    include VIEWS_DIR . '/errors/404.php';
    exit;
}
