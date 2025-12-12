<?php
/**
 * Application Constants
 * ABIS - Aplikasi Desa Digital
 */

// Application Information
define('APP_NAME', 'ABIS - Aplikasi Desa Digital');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Sistem pengelolaan surat menyurat desa secara digital');
define('APP_AUTHOR', 'ABIS Development Team');

// Base URL (adjust according to your setup)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Remove trailing slash from script name if it exists
$scriptName = rtrim($scriptName, '/');

define('BASE_URL', $protocol . '://' . $host . $scriptName);
define('ASSETS_URL', BASE_URL . '/public/assets');
define('UPLOADS_URL', BASE_URL . '/public/uploads');

// Directory Paths
define('ROOT_DIR', dirname(__DIR__));
define('APP_DIR', ROOT_DIR . '/app');
define('CONFIG_DIR', ROOT_DIR . '/config');
define('PUBLIC_DIR', ROOT_DIR . '/public');
define('UPLOADS_DIR', PUBLIC_DIR . '/uploads');
define('VIEWS_DIR', APP_DIR . '/views');
define('MODELS_DIR', APP_DIR . '/models');
define('CONTROLLERS_DIR', APP_DIR . '/controllers');

// File Upload Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx']);
define('UPLOAD_MAX_WIDTH', 1920);
define('UPLOAD_MAX_HEIGHT', 1080);

// Pagination Settings
define('ITEMS_PER_PAGE', 10);
define('MAX_PAGES_DISPLAY', 5);

// Date and Time Settings
define('DEFAULT_TIMEZONE', 'Asia/Jakarta');
date_default_timezone_set(DEFAULT_TIMEZONE);
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'd/m/Y');
define('DISPLAY_DATETIME_FORMAT', 'd/m/Y H:i');

// Letter Request Status
define('STATUS_PENDING', 'pending');
define('STATUS_APPROVED', 'approved');
define('STATUS_REJECTED', 'rejected');
define('STATUS_COMPLETED', 'completed');

// User Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_USER', 'user');

// Telegram Bot Settings (will be loaded from database)
define('TELEGRAM_API_URL', 'https://api.telegram.org/bot');
define('TELEGRAM_FILE_URL', 'https://api.telegram.org/file/bot');

// Security Settings
define('PASSWORD_MIN_LENGTH', 8);
define('CSRF_TOKEN_LENGTH', 32);
define('SESSION_TIMEOUT', 3600); // 1 hour

// Error Messages
define('ERROR_INVALID_CREDENTIALS', 'Username atau password salah');
define('ERROR_ACCESS_DENIED', 'Akses ditolak');
define('ERROR_PAGE_NOT_FOUND', 'Halaman tidak ditemukan');
define('ERROR_METHOD_NOT_ALLOWED', 'Metode tidak diizinkan');
define('ERROR_INVALID_REQUEST', 'Permintaan tidak valid');
define('ERROR_FILE_TOO_LARGE', 'Ukuran file terlalu besar');
define('ERROR_INVALID_FILE_TYPE', 'Tipe file tidak diizinkan');
define('ERROR_UPLOAD_FAILED', 'Upload file gagal');
define('ERROR_DATABASE_ERROR', 'Terjadi kesalahan database');

// Success Messages
define('SUCCESS_LOGIN', 'Login berhasil');
define('SUCCESS_LOGOUT', 'Logout berhasil');
define('SUCCESS_REGISTER', 'Registrasi berhasil');
define('SUCCESS_REQUEST_CREATED', 'Permohonan surat berhasil dibuat');
define('SUCCESS_REQUEST_UPDATED', 'Permohonan surat berhasil diperbarui');
define('SUCCESS_FILE_UPLOADED', 'File berhasil diupload');
define('SUCCESS_PASSWORD_CHANGED', 'Password berhasil diubah');

// Letter Types (can be extended)
define('LETTER_TYPES', [
    'SKD' => 'Surat Keterangan Domisili',
    'SKU' => 'Surat Keterangan Usaha',
    'SPN' => 'Surat Pengantar Nikah',
    'SKTM' => 'Surat Keterangan Tidak Mampu'
]);

// Required Fields for Each Letter Type
define('LETTER_REQUIRED_FIELDS', [
    'SKD' => ['nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'keperluan'],
    'SKU' => ['nik', 'nama', 'alamat_usaha', 'jenis_usaha', 'lama_usaha'],
    'SPN' => ['nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'nama_pasangan'],
    'SKTM' => ['nik', 'nama', 'alamat', 'pekerjaan', 'penghasilan']
]);
