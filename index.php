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
require_once CONTROLLERS_DIR . '/admin/DashboardController.php';
require_once CONTROLLERS_DIR . '/admin/LetterRequestController.php';
require_once CONTROLLERS_DIR . '/admin/SettingsController.php';

// Create controller instances
$homeController = new HomeController();
$authController = new AuthController();
$userController = new UserController();
$adminController = new AdminController();
$dashboardController = new DashboardController();
$letterRequestController = new LetterRequestController();
$settingsController = new SettingsController();

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

$router->add('GET', '/profile', function() {
    global $userController;
    requireAuth('user');
    $userController->profile();
});

$router->add('POST', '/profile', function() {
    global $userController;
    requireAuth('user');
    $userController->updateProfile();
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

// Test user status page
$router->add('GET', '/test-status', function() {
    include 'test_user_status.php';
    exit;
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

$router->add('GET', '/requests/:id/preview', function($params) {
    global $userController;
    requireAuth('user');
    $userController->previewRequest($params['id']);
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

// Letter requests management (approve/reject)
$router->add('GET', '/admin/letter-requests', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->letterRequests();
});

// Export Excel route
$router->add('GET', '/admin/export/excel', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->exportExcel();
});

// API route for export data
$router->add('GET', '/admin/api/export-data', function() {
    global $adminController;
    requireAuth('admin');
    $adminController->getExportData();
});

// Telegram Bot Settings
$router->add('GET', '/admin/telegram-settings', function() {
    global $adminController;
    $adminController->telegramSettings();
});

$router->add('POST', '/admin/telegram-settings', function() {
    global $adminController;
    $adminController->telegramSettings();
});

// User Telegram Chat ID Update
$router->add('POST', '/telegram/update-chat-id', function() {
    global $userController;
    requireAuth('user');
    $userController->updateTelegramChatId();
});

// Telegram Webhook
$router->add('POST', '/telegram/webhook', function() {
    require_once 'telegram_webhook.php';
});

// Cetak Surat dari Database
$router->add('GET', '/cetak-surat', function() {
    require_once 'public/cetak-surat.php';
});

// New Refactored Admin Routes
// Dashboard
$router->add('GET', '/admin/dashboard', function() {
    global $dashboardController;
    requireAuth('admin');
    $dashboardController->index();
});

$router->add('GET', '/admin/dashboard/stats', function() {
    global $dashboardController;
    requireAuth('admin');
    $dashboardController->getStats();
});

// Letter Requests
$router->add('GET', '/admin/requests', function() {
    global $letterRequestController;
    requireAuth('admin');
    $letterRequestController->index();
});

$router->add('GET', '/admin/requests/:id', function($params) {
    global $letterRequestController;
    requireAuth('admin');
    $letterRequestController->show($params['id']);
});

$router->add('POST', '/admin/requests/:id/approve', function($params) {
    global $letterRequestController;
    requireAuth('admin');
    $letterRequestController->approve($params['id']);
});

$router->add('POST', '/admin/requests/:id/reject', function($params) {
    global $letterRequestController;
    requireAuth('admin');
    $letterRequestController->reject($params['id']);
});

$router->add('GET', '/admin/requests/:id/download', function($params) {
    global $letterRequestController;
    requireAuth('admin');
    $letterRequestController->download($params['id']);
});

$router->add('POST', '/admin/requests/:id/delete', function($params) {
    global $letterRequestController;
    requireAuth('admin');
    $letterRequestController->delete($params['id']);
});

// Settings
$router->add('GET', '/admin/settings/telegram', function() {
    global $settingsController;
    requireAuth('admin');
    $settingsController->telegram();
});

$router->add('POST', '/admin/settings/telegram', function() {
    global $settingsController;
    requireAuth('admin');
    $settingsController->telegram();
});

// API routes for AJAX requests
$router->add('GET', '/api/letter-types', function() {
    requireAuth();
    header('Content-Type: application/json');
    $letterTypes = fetchAll("SELECT * FROM letter_types WHERE is_active = 1 ORDER BY name");
    echo json_encode($letterTypes);
});

// API endpoint for request details (used by user requests page)
$router->add('GET', '/api/requests/:id', function($params) {
    requireAuth();
    header('Content-Type: application/json');

    try {
        $requestId = (int) $params['id'];
        $userId = getCurrentUserId();

        // Get request details
        $letterRequestModel = new LetterRequest();
        $request = $letterRequestModel->findById($requestId);

        if (!$request) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Permohonan tidak ditemukan']);
            return;
        }

        // Check if user owns this request
        if ($request['user_id'] != $userId) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            return;
        }

        // Get letter type info
        $letterTypeModel = new LetterType();
        $letterType = $letterTypeModel->findById($request['letter_type_id']);

        // Get user info
        $userModel = new User();
        $user = $userModel->findById($request['user_id']);

        // Prepare response data
        $responseData = [
            'id' => $request['id'],
            'status' => $request['status'],
            'created_at' => $request['created_at'],
            'approved_at' => $request['approved_at'],
            'letter_type_name' => $letterType ? $letterType['name'] : 'Tidak diketahui',
            'user_full_name' => $user ? $user['full_name'] : 'Tidak diketahui',
            'user_nik' => $user ? $user['nik'] : '',
            'user_email' => $user ? $user['email'] : '',
            'request_data' => $request['request_data'],
            'admin_notes' => $request['admin_notes'],
            'generated_file' => $request['generated_file']
        ];

        echo json_encode(['success' => true, 'request' => $responseData]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
    }
});

// API endpoint for PDF preview
$router->add('GET', '/api/requests/:id/preview', function($params) {
    requireAuth();

    try {
        $requestId = (int) $params['id'];
        $userId = getCurrentUserId();

        // Get request details
        $letterRequestModel = new LetterRequest();
        $request = $letterRequestModel->findById($requestId);

        if (!$request) {
            http_response_code(404);
            die('Permohonan tidak ditemukan');
        }

        // Check if user owns this request
        if ($request['user_id'] != $userId) {
            http_response_code(403);
            die('Akses ditolak');
        }

        // Check if request is approved and has generated file
        if ($request['status'] !== STATUS_APPROVED || empty($request['generated_file'])) {
            http_response_code(404);
            die('PDF belum tersedia');
        }

        // Get PDF file path
        $letterService = new LetterService();
        $filePath = $letterService->getLetterFilePath($requestId);

        if (!$filePath || !file_exists($filePath)) {
            http_response_code(404);
            die('File PDF tidak ditemukan');
        }

        // Set headers for PDF display
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        // Output file
        readfile($filePath);
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        die('Terjadi kesalahan server');
    }
});

// API endpoint for request download
$router->add('GET', '/api/requests/:id/download', function($params) {
    requireAuth();

    try {
        $requestId = (int) $params['id'];
        $userId = getCurrentUserId();

        // Get request details
        $letterRequestModel = new LetterRequest();
        $request = $letterRequestModel->findById($requestId);

        if (!$request) {
            http_response_code(404);
            die('Permohonan tidak ditemukan');
        }

        // Check if user owns this request
        if ($request['user_id'] != $userId) {
            http_response_code(403);
            die('Akses ditolak');
        }

        // Check if request is approved and has generated file
        if ($request['status'] !== STATUS_APPROVED || empty($request['generated_file'])) {
            http_response_code(404);
            die('PDF belum tersedia');
        }

        // Get PDF file path
        $letterService = new LetterService();
        $filePath = $letterService->getLetterFilePath($requestId);

        if (!$filePath || !file_exists($filePath)) {
            http_response_code(404);
            die('File PDF tidak ditemukan');
        }

        // Set headers for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="surat_' . $requestId . '.pdf"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        // Clear output buffer
        if (ob_get_level()) {
            ob_clean();
        }

        // Output file
        readfile($filePath);
        exit;

    } catch (Exception $e) {
        http_response_code(500);
        die('Terjadi kesalahan server');
    }
});

$router->add('GET', '/api/letter-types/:id/fields', function($params) {
    requireAuth();
    header('Content-Type: application/json');

    $letterTypeId = (int) $params['id'];
    $letterTypeModel = new LetterType();

    // Get letter type details
    $letterType = $letterTypeModel->findById($letterTypeId);
    if (!$letterType) {
        http_response_code(404);
        echo json_encode(['error' => 'Letter type not found']);
        return;
    }

    // Get required fields configuration
    $requiredFields = $letterTypeModel->getRequiredFields($letterTypeId);

    // Define all manual fields (no auto-fill from profile)
    $allFields = [
        // Personal information fields (manual entry)
        'nama' => ['label' => 'Nama Lengkap', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan nama lengkap', 'icon' => 'user'],
        'nik' => ['label' => 'NIK', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan 16 digit NIK', 'icon' => 'id-card'],
        'tempat_lahir' => ['label' => 'Tempat Lahir', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan tempat lahir', 'icon' => 'map'],
        'tanggal_lahir' => ['label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true, 'icon' => 'calendar'],
        'jenis_kelamin' => ['label' => 'Jenis Kelamin', 'type' => 'select', 'required' => true, 'options' => ['Laki-laki', 'Perempuan'], 'icon' => 'venus-mars'],
        'agama' => ['label' => 'Agama', 'type' => 'select', 'required' => true, 'options' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'], 'icon' => 'pray'],
        'pekerjaan' => ['label' => 'Pekerjaan', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan pekerjaan', 'icon' => 'briefcase'],
        'alamat' => ['label' => 'Alamat', 'type' => 'textarea', 'required' => true, 'placeholder' => 'Masukkan alamat lengkap', 'icon' => 'map-marker'],

        // Letter-specific fields
        'keperluan' => ['label' => 'Keperluan', 'type' => 'textarea', 'required' => true, 'placeholder' => 'Jelaskan keperluan pembuatan surat', 'icon' => 'clipboard-list'],
        'alamat_domisili' => ['label' => 'Alamat Domisili', 'type' => 'textarea', 'required' => true, 'placeholder' => 'Alamat domisili saat ini', 'icon' => 'home'],

        // Business fields
        'nama_usaha' => ['label' => 'Nama Usaha', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama usaha atau bisnis', 'icon' => 'building'],
        'jenis_usaha' => ['label' => 'Jenis Usaha', 'type' => 'text', 'required' => true, 'placeholder' => 'Jenis kegiatan usaha', 'icon' => 'industry'],
        'alamat_usaha' => ['label' => 'Alamat Usaha', 'type' => 'textarea', 'required' => true, 'placeholder' => 'Lokasi usaha', 'icon' => 'map-marker'],

        // Education fields
        'sekolah' => ['label' => 'Asal Sekolah/Kampus', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama sekolah atau universitas', 'icon' => 'school'],
        'nis_nim' => ['label' => 'NIS/NIM', 'type' => 'text', 'required' => true, 'placeholder' => 'Nomor Induk Siswa/Mahasiswa', 'icon' => 'graduation-cap'],
        'jurusan' => ['label' => 'Jurusan/Program Studi', 'type' => 'text', 'required' => true, 'placeholder' => 'Jurusan atau program studi', 'icon' => 'book'],
        'semester' => ['label' => 'Semester', 'type' => 'number', 'required' => true, 'placeholder' => 'Semester saat ini', 'icon' => 'clock', 'min' => 1, 'max' => 14],
        'nama_beasiswa' => ['label' => 'Nama Beasiswa', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama program beasiswa', 'icon' => 'trophy'],
        'nama_ayah' => ['label' => 'Nama Ayah/Wali', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama lengkap ayah atau wali', 'icon' => 'user-father'],

        // Family fields
        'nik_pasangan' => ['label' => 'NIK Pasangan', 'type' => 'text', 'required' => true, 'placeholder' => 'NIK calon pasangan', 'icon' => 'heart'],
        'nama_pasangan' => ['label' => 'Nama Pasangan', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama lengkap calon pasangan', 'icon' => 'user'],

        // Event fields
        'nama_kegiatan' => ['label' => 'Nama Kegiatan', 'type' => 'text', 'required' => true, 'placeholder' => 'Nama acara atau kegiatan', 'icon' => 'calendar-event'],
        'tanggal_kegiatan' => ['label' => 'Tanggal Kegiatan', 'type' => 'date', 'required' => true, 'icon' => 'calendar-day'],
        'waktu_kegiatan' => ['label' => 'Waktu Kegiatan', 'type' => 'text', 'required' => true, 'placeholder' => 'Waktu pelaksanaan', 'icon' => 'clock'],
        'tempat_kegiatan' => ['label' => 'Tempat Kegiatan', 'type' => 'textarea', 'required' => true, 'placeholder' => 'Lokasi kegiatan', 'icon' => 'map-marker'],
        'hiburan' => ['label' => 'Hiburan/Entertainment', 'type' => 'text', 'required' => true, 'placeholder' => 'Jenis hiburan (jika ada)', 'icon' => 'music'],

        // Financial fields
        'penghasilan' => ['label' => 'Penghasilan', 'type' => 'number', 'required' => true, 'placeholder' => 'Penghasilan per bulan (Rp)', 'icon' => 'money-bill', 'min' => 0]
    ];

    // Map letter type codes to their required fields (including personal info)
    $letterTypeFieldMap = [
        'SKD' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'keperluan', 'alamat_domisili'], // Surat Keterangan Domisili
        'SKU' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nama_usaha', 'jenis_usaha', 'alamat_usaha', 'keperluan'], // Surat Keterangan Usaha
        'SKTM' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'penghasilan', 'keperluan'], // Surat Keterangan Tidak Mampu
        'IZU' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nama_usaha', 'jenis_usaha', 'alamat_usaha', 'keperluan'], // Izin Usaha
        'SRB' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'sekolah', 'nis_nim', 'jurusan', 'semester', 'nama_beasiswa', 'nama_ayah', 'keperluan'], // Surat Rekomendasi Beasiswa
        'SPN' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nik_pasangan', 'nama_pasangan', 'keperluan'], // Surat Pengantar Nikah
        'IZK' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nama_kegiatan', 'tanggal_kegiatan', 'waktu_kegiatan', 'tempat_kegiatan', 'hiburan', 'keperluan'], // Izin Kegiatan
        'SKBM' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'keperluan'], // Surat Keterangan Belum Menikah
        'SK' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'keperluan'], // Surat Keterangan (umum)
        'SIK' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nama_kegiatan', 'tanggal_kegiatan', 'waktu_kegiatan', 'tempat_kegiatan', 'hiburan', 'keperluan'], // Surat Izin Kegiatan (alias)
        'SIU' => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nama_usaha', 'jenis_usaha', 'alamat_usaha', 'keperluan'] // Surat Izin Usaha (alias)
    ];

    // Build response based on letter type
    $response = [
        'letter_type' => [
            'id' => $letterType['id'],
            'name' => $letterType['name'],
            'code' => $letterType['code'],
            'description' => $letterType['description']
        ],
        'field_categories' => []
    ];

    // Single category for all manual fields
    $response['field_categories']['manual'] = [
        'title' => 'Formulir Surat',
        'description' => 'Silakan isi semua data yang diperlukan untuk pembuatan surat',
        'icon' => 'edit',
        'fields' => []
    ];

    // Add all required fields for this letter type
    $requiredFieldNames = $letterTypeFieldMap[$letterType['code']] ?? [];

    foreach ($requiredFieldNames as $fieldName) {
        if (isset($allFields[$fieldName])) {
            $response['field_categories']['manual']['fields'][] = array_merge(
                $allFields[$fieldName],
                ['name' => $fieldName, 'category' => 'manual']
            );
        }
    }

    echo json_encode($response);
});

// Route to serve template files (images for PDF generation)
$router->add('GET', '/templates/:filename', function($params) {
    $filename = $params['filename'];
    $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'svg'];

    // Check if file extension is allowed
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions)) {
        http_response_code(403);
        echo 'Forbidden file type';
        return;
    }

    $filePath = __DIR__ . '/templates/' . $filename;

    // Check if file exists
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo 'File not found';
        return;
    }

    // Set appropriate content type
    $contentTypes = [
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml'
    ];

    $contentType = $contentTypes[$extension] ?? 'application/octet-stream';
    header('Content-Type: ' . $contentType);
    header('Cache-Control: public, max-age=31536000'); // Cache for 1 year

    // Serve the file
    readfile($filePath);
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
