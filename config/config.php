<?php
/**
 * Main Configuration File
 * ABIS - Aplikasi Desa Digital
 *
 * This file loads all configuration files
 */
// Tambahkan blok ini di paling atas
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}
// Load constants
require_once __DIR__ . '/constants.php';

// Load database configuration
require_once __DIR__ . '/database.php';

// Load session configuration
require_once __DIR__ . '/session.php';

/**
 * Initialize application
 */
function initApp() {
    // Start session
    startSession();

    // Set error reporting based on environment
    if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', ROOT_DIR . '/logs/error.log');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    // Create necessary directories if they don't exist
    $directories = [UPLOADS_DIR, ROOT_DIR . '/logs'];
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    // Set default headers
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');

    // Prevent caching for development
    if (!defined('ENVIRONMENT') || ENVIRONMENT !== 'production') {
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}

/**
 * Get application settings from database
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function getSetting($key, $default = null) {
    try {
        $setting = fetchOne(
            "SELECT setting_value, setting_type FROM settings WHERE setting_key = ?",
            [$key]
        );

        if (!$setting) {
            return $default;
        }

        switch ($setting['setting_type']) {
            case 'integer':
                return (int) $setting['setting_value'];
            case 'boolean':
                return (bool) $setting['setting_value'];
            case 'json':
                return json_decode($setting['setting_value'], true);
            default:
                return $setting['setting_value'];
        }
    } catch (Exception $e) {
        return $default;
    }
}

/**
 * Update application setting
 * @param string $key
 * @param mixed $value
 * @param string $type
 * @return bool
 */
function updateSetting($key, $value, $type = 'string') {
    try {
        $existing = fetchOne("SELECT id FROM settings WHERE setting_key = ?", [$key]);

        if ($existing) {
            return update(
                'settings',
                ['setting_value' => (string) $value, 'setting_type' => $type],
                'setting_key = ?',
                [$key]
            ) > 0;
        } else {
            return insert('settings', [
                'setting_key' => $key,
                'setting_value' => (string) $value,
                'setting_type' => $type,
                'description' => 'Auto-generated setting'
            ]) > 0;
        }
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Sanitize input data
 * @param mixed $data
 * @return mixed
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 * @return string
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(CSRF_TOKEN_LENGTH / 2));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token
 * @return bool
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate random string
 * @param int $length
 * @return string
 */
function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Format file size
 * @param int $bytes
 * @return string
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

/**
 * Get file extension
 * @param string $filename
 * @return string
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Check if file type is allowed
 * @param string $filename
 * @return bool
 */
function isAllowedFileType($filename) {
    $extension = getFileExtension($filename);
    return in_array($extension, ALLOWED_FILE_TYPES);
}

/**
 * Generate unique filename
 * @param string $originalName
 * @param string $prefix
 * @return string
 */
function generateUniqueFilename($originalName, $prefix = '') {
    $extension = getFileExtension($originalName);
    $timestamp = time();
    $random = generateRandomString(8);
    return $prefix . $timestamp . '_' . $random . '.' . $extension;
}
