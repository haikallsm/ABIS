<?php
/**
 * Session Configuration
 * ABIS - Aplikasi Desa Digital
 */

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS in production
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', 3600); // 1 hour
ini_set('session.cookie_lifetime', 3600); // 1 hour

// Session settings
define('SESSION_NAME', 'ABIS_SESSION');
define('SESSION_LIFETIME', 3600); // 1 hour in seconds

/**
 * Start session with custom settings
 */
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_start();

        // Regenerate session ID periodically for security
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } else if (time() - $_SESSION['created'] > SESSION_LIFETIME) {
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if current user is admin
 * @return bool
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Check if current user is regular user
 * @return bool
 */
function isUser() {
    return isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user';
}

/**
 * Get current user ID
 * @return int|null
 */
function getCurrentUserId() {
    return isLoggedIn() ? $_SESSION['user_id'] : null;
}

/**
 * Get current user role
 * @return string|null
 */
function getCurrentUserRole() {
    return isLoggedIn() ? $_SESSION['user_role'] : null;
}

/**
 * Get current user data
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'full_name' => $_SESSION['full_name'],
        'role' => $_SESSION['user_role']
    ];
}

/**
 * Set user session data
 * @param array $user
 */
function setUserSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['created'] = time();
}

/**
 * Clear user session
 */
function clearUserSession() {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Clear the session cookie
    if (isset($_COOKIE[SESSION_NAME])) {
        setcookie(SESSION_NAME, '', time() - 3600, '/');
    }
}

/**
 * Require authentication (redirect to login if not authenticated)
 * @param string $role Optional role requirement ('admin' or 'user')
 */
function requireAuth($role = null) {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    if ($role === 'admin' && !isAdmin()) {
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }

    if ($role === 'user' && !isUser()) {
        header('Location: ' . BASE_URL . '/admin/dashboard');
        exit;
    }
}

/**
 * Redirect authenticated users (prevent access to login/register pages)
 */
function redirectIfAuthenticated() {
    if (isLoggedIn()) {
        $redirectUrl = isAdmin() ? '/admin/dashboard' : '/dashboard';
        header('Location: ' . BASE_URL . $redirectUrl);
        exit;
    }
}
