<?php
/**
 * User Controller
 * Handles user-side operations (dashboard, requests, etc.)
 * ABIS - Aplikasi Desa Digital
 */

require_once MODELS_DIR . '/User.php';
require_once MODELS_DIR . '/LetterRequest.php';
require_once MODELS_DIR . '/LetterType.php';

class UserController {
    private $userModel;
    private $requestModel;
    private $letterTypeModel;

    public function __construct() {
        $this->userModel = new User();
        $this->requestModel = new LetterRequest();
        $this->letterTypeModel = new LetterType();
    }

    /**
     * Show user dashboard
     */
    public function dashboard() {
        requireAuth('user');

        $userId = getCurrentUserId();
        $current_user = getCurrentUser();

        // Get recent requests (last 5)
        $recent_requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code
             FROM letter_requests lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             WHERE lr.user_id = ?
             ORDER BY lr.created_at DESC
             LIMIT 5",
            [$userId]
        );

        // Get all requests for history (limit to prevent memory issues)
        $all_requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code
             FROM letter_requests lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             WHERE lr.user_id = ?
             ORDER BY lr.created_at DESC
             LIMIT 50",
            [$userId]
        );

        // Get available letter types
        $letter_types = $this->letterTypeModel->getAllActive();

        // Get request statistics for user
        $stats = $this->getUserStats($userId);

        // Pass data to view using renderView method
        $this->renderView('user/dashboard', [
            'title' => 'Dashboard User',
            'extra_css' => ['user-sidebar.css', 'user-dashboard.css'],
            'extra_js' => ['user-navigation.js'],
            'current_user' => $current_user,
            'stats' => $stats,
            'recent_requests' => $recent_requests,
            'all_requests' => $all_requests,
            'letter_types' => $letter_types
        ]);
    }

    /**
     * Show create request form
     */
    public function createRequest() {
        requireAuth('user');

        $letterTypes = $this->letterTypeModel->getAllActive();
        $this->renderView('user/create_request', [
            'title' => 'Buat Permohonan Surat - ' . APP_NAME,
            'letterTypes' => $letterTypes
        ]);
    }

    /**
     * Show user's request history
     */
    public function requests() {
        requireAuth('user');

        $userId = getCurrentUserId();
        $result = $this->requestModel->findByUserId($userId);

        $this->renderView('user/requests', [
            'title' => 'Riwayat Permohonan - ' . APP_NAME,
            'requests' => $result['requests'],
            'total' => $result['total'],
            'pages' => $result['pages'],
            'current_page' => $result['current_page']
        ]);
    }

    /**
     * Process create request form
     */
    public function processCreateRequest() {
        // Debug logging
        error_log("UserController::processCreateRequest called");
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("Session data: " . json_encode($_SESSION ?? []));

        requireAuth('user');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Invalid request method, redirecting to create form");
            header('Location: ' . BASE_URL . '/requests/create');
            exit;
        }

        $userId = getCurrentUserId();
        error_log("Current user ID: " . $userId);
        $letterTypeId = (int) ($_POST['letter_type_id'] ?? 0);
        error_log("Letter type ID: " . $letterTypeId);

        // Validate letter type
        $letterType = $this->letterTypeModel->findById($letterTypeId);
        error_log("Letter type validation: " . ($letterType ? "FOUND - {$letterType['name']}" : "NOT FOUND"));

        if (!$letterType) {
            error_log("Letter type validation failed, redirecting with error");
            $_SESSION['error'] = 'Jenis surat tidak valid';
            header('Location: ' . BASE_URL . '/requests/create');
            exit;
        }

        // Dynamic form validation - get required fields from API configuration
        $requiredFields = $this->getRequiredFieldsForLetterType($letterTypeId);

        // Get and validate form data (dynamic form sends all fields)
        $requestData = [];
        $errors = [];

        error_log("POST data received: " . json_encode($_POST));
        error_log("Required fields for letter type {$letterTypeId}: " . json_encode($requiredFields));

        foreach ($requiredFields as $field => $config) {
            $rawValue = $_POST[$field] ?? null;
            $value = sanitize($rawValue ?? '');

            error_log("Processing field '{$field}': raw='{$rawValue}', sanitized='{$value}', required=" . ($config['required'] ? 'true' : 'false'));

            // Check if field is required - strict validation
            if ($config['required']) {
                // Check if field exists in POST data
                if (!isset($_POST[$field])) {
                    $errors[$field] = "Field '{$config['label']}' tidak ditemukan dalam form";
                    error_log("Field '{$field}' not found in POST data");
                }
                // Check if field is not empty (after trimming whitespace)
                elseif (empty(trim($_POST[$field]))) {
                    $errors[$field] = "Field '{$config['label']}' wajib diisi";
                    error_log("Field '{$field}' is empty");
                }
                // Additional validation for specific field types
                elseif ($field === 'nik' && strlen(trim($_POST[$field])) !== 16) {
                    $errors[$field] = "NIK harus 16 digit";
                    error_log("Field '{$field}' NIK validation failed");
                }
                elseif ($field === 'nama' && strlen(trim($_POST[$field])) < 2) {
                    $errors[$field] = "Nama harus minimal 2 karakter";
                    error_log("Field '{$field}' name validation failed");
                }
            }

            // Only include non-empty values or required fields
            if (!empty($value) || $config['required']) {
                $requestData[$field] = $value;
                error_log("Including field '{$field}' with value '{$value}'");
            } else {
                error_log("Skipping field '{$field}' - empty and not required");
            }
        }

        error_log("Final requestData: " . json_encode($requestData));
        error_log("Validation errors: " . json_encode($errors));

        // Additional validation for specific field types
        foreach ($_POST as $field => $value) {
            if ($field === 'letter_type_id' || $field === 'csrf_token') continue;

            $value = sanitize($value);

            // Email validation
            if (strpos($field, 'email') !== false && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = 'Format email tidak valid';
            }

            // Numeric validation
            if (in_array($field, ['nik', 'nis_nim', 'semester']) && !empty($value) && !is_numeric($value)) {
                $errors[$field] = 'Field ini harus berupa angka';
            }

            // NIK length validation
            if ($field === 'nik' && !empty($value) && strlen($value) !== 16) {
                $errors[$field] = 'NIK harus 16 digit';
            }
        }

        if (!empty($errors)) {
            error_log("Validation errors: " . json_encode($errors));
            $this->renderView('user/create_request', [
                'title' => 'Buat Permohonan Surat - ' . APP_NAME,
                'letterTypes' => $this->letterTypeModel->getAllActive(),
                'errors' => $errors,
                'old' => array_merge(['letter_type_id' => $letterTypeId], $_POST)
            ]);
            return;
        }

        // Create request using enhanced data separation
        $formData = array_merge([
            'user_id' => $userId,
            'letter_type_id' => $letterTypeId
        ], $requestData);

        error_log("Attempting to create request with form data: " . json_encode($formData));

        if ($this->requestModel->createWithDataSeparation($formData)) {
            error_log("Request created successfully");
            $_SESSION['success'] = SUCCESS_REQUEST_CREATED;
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        } else {
            error_log("Request creation failed - check database connection and constraints");
            $_SESSION['error'] = 'Terjadi kesalahan saat membuat permohonan. Silakan coba lagi.';
            header('Location: ' . BASE_URL . '/requests/create');
            exit;
        }
    }

    /**
     * View specific request
     * @param int $requestId
     */
    public function viewRequest($requestId) {
        requireAuth('user');

        // Redirect to dashboard for now (view_request view doesn't exist)
        $userId = getCurrentUserId();
        $request = $this->requestModel->findById($requestId);

        if (!$request || $request['user_id'] != $userId) {
            http_response_code(404);
            exit('Permohonan tidak ditemukan');
        }

        $this->renderView('user/view_request', [
            'title' => 'Detail Surat',
            'request' => $request
        ]);
    }

    /**
     * Download generated document
     * @param int $requestId
     */
    public function downloadRequest($requestId) {
        requireAuth('user');

        $userId = getCurrentUserId();
        $request = $this->requestModel->findById($requestId);

        if (!$request || $request['user_id'] != $userId) {
            http_response_code(404);
            exit('Request tidak ditemukan');
        }

        if (empty($request['generated_file'])) {
            http_response_code(404);
            exit('File tidak tersedia');
        }

        $filePath = UPLOADS_DIR . '/' . $request['generated_file'];

        if (!file_exists($filePath)) {
            http_response_code(404);
            exit('File tidak ditemukan');
        }

        // Get file info
        $fileName = basename($filePath);
        $fileSize = filesize($filePath);

        // Set headers for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        // Clear output buffer
        ob_clean();
        flush();

        // Read and output file
        readfile($filePath);
        exit;
    }

    /**
     * Preview PDF request inline in browser (without download)
     * @param int $requestId
     */
    public function previewRequest($requestId) {
        requireAuth('user');

        $userId = getCurrentUserId();
        $request = $this->requestModel->findById($requestId);

        if (!$request || $request['user_id'] != $userId) {
            http_response_code(404);
            exit('Request tidak ditemukan');
        }

        if ($request['status'] !== 'approved') {
            http_response_code(403);
            exit('Surat belum disetujui');
        }

        if (empty($request['generated_file'])) {
            http_response_code(404);
            exit('File tidak tersedia');
        }

        $filePath = UPLOADS_DIR . '/' . $request['generated_file'];

        if (!file_exists($filePath)) {
            http_response_code(404);
            exit('File tidak ditemukan');
        }

        // Get file info
        $fileSize = filesize($filePath);

        // Set headers for inline preview (not download)
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        header('Accept-Ranges: bytes');

        // Clear output buffer
        ob_clean();
        flush();

        // Read and output file
        readfile($filePath);
        exit;
    }

    /**
     * Get user statistics
     * @param int $userId
     * @return array
     */
    private function getUserStats($userId) {
        $stats = [
            'total_requests' => 0,
            'pending_requests' => 0,
            'completed_requests' => 0
        ];

        $userRequests = fetchAll(
            "SELECT status, COUNT(*) as count FROM letter_requests WHERE user_id = ? GROUP BY status",
            [$userId]
        );

        foreach ($userRequests as $stat) {
            $status = $stat['status'];
            $count = (int) $stat['count'];

            $stats['total_requests'] += $count;

            if ($status === 'pending') {
                $stats['pending_requests'] = $count;
            } elseif (in_array($status, ['approved', 'completed'])) {
                $stats['completed_requests'] += $count;
            }
        }

        return $stats;
    }

    /**
     * Get icon for letter type
     * @param string $typeCode
     * @return string
     */
    public function getIconForType($typeCode) {
        $icons = [
            'SKD' => 'file-medical',
            'SKU' => 'store',
            'SPN' => 'ring',
            'SKTM' => 'file-invoice'
        ];

        return $icons[$typeCode] ?? 'file-alt';
    }

    /**
     * Render view with layout
     * @param string $view
     * @param array $data
     */
    private function renderView($view, $data = []) {
        // Debug: Log data being passed to view
        error_log("UserController::renderView - Data for {$view}: " . json_encode(array_keys($data)));

        // Extract data to make variables available in view
        extract($data);

        // Start output buffering to capture view content
        ob_start();
        $viewPath = VIEWS_DIR . '/' . $view . '.php';
        if (!file_exists($viewPath)) {
            error_log("UserController::renderView - View file not found: {$viewPath}");
            die("View file not found: {$view}");
        }
        include $viewPath;
        $content = ob_get_clean();

        // Include layout
        require VIEWS_DIR . '/layouts/user.php';
    }

    /**
     * Show user profile
     */
    public function profile() {
        requireAuth('user');

        $userId = getCurrentUserId();
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'User tidak ditemukan.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        // Pass user data to view
        $this->renderView('user/profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile() {
        requireAuth('user');

        $userId = getCurrentUserId();

        $data = [
            'nik' => $_POST['nik'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'birth_place' => $_POST['birth_place'] ?? '',
            'birth_date' => $_POST['birth_date'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'address' => $_POST['address'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'occupation' => $_POST['occupation'] ?? '',
            'religion' => $_POST['religion'] ?? '',
            'marital_status' => $_POST['marital_status'] ?? ''
        ];

        // Validation
        if (empty($data['nik']) || empty($data['full_name'])) {
            $_SESSION['error'] = 'NIK dan Nama Lengkap harus diisi.';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        if (!is_numeric($data['nik']) || strlen($data['nik']) !== 16) {
            $_SESSION['error'] = 'NIK harus berupa 16 digit angka.';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $result = $this->userModel->updateProfile($userId, $data);

        if ($result) {
            $_SESSION['success'] = 'Profile berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui profile.';
        }

        header('Location: ' . BASE_URL . '/profile');
        exit;
    }

    /**
     * Update user's Telegram Chat ID
     */
    public function updateTelegramChatId() {
        requireAuth('user');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $chatId = sanitize($_POST['telegram_chat_id'] ?? '');
        $userId = getCurrentUserId();

        if (empty($chatId)) {
            $_SESSION['error'] = 'Chat ID Telegram tidak boleh kosong.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        // Validate Chat ID format (should be numeric)
        if (!is_numeric($chatId)) {
            $_SESSION['error'] = 'Chat ID Telegram harus berupa angka.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        // Update user's telegram_chat_id
        $result = $this->userModel->update($userId, ['telegram_chat_id' => $chatId]);

        if ($result) {
            $_SESSION['success'] = 'Chat ID Telegram berhasil diperbarui. Anda akan menerima notifikasi via Telegram.';

            // Test the connection by sending a welcome message
            try {
                require_once 'utils/TelegramBot.php';
                $telegramBot = new TelegramBot();
                $telegramBot->sendMessage($chatId, "🎉 Akun Telegram Anda berhasil terhubung!\n\nAnda akan menerima notifikasi ketika status permohonan surat Anda berubah.");
            } catch (Exception $e) {
                // Don't show error if bot is not configured, just silently fail
                error_log('Telegram bot test message failed: ' . $e->getMessage());
            }
        } else {
            $_SESSION['error'] = 'Gagal memperbarui Chat ID Telegram.';
        }

        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }

    /**
     * Get required fields configuration for a letter type (Dynamic Form Support)
     * @param int $letterTypeId
     * @return array
     */
    private function getRequiredFieldsForLetterType($letterTypeId) {
        // Define all manual fields (no auto-fill from profile)
        $allFields = [
            // Personal information fields (manual entry)
            'nama' => ['label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
            'nik' => ['label' => 'NIK', 'type' => 'text', 'required' => true],
            'tempat_lahir' => ['label' => 'Tempat Lahir', 'type' => 'text', 'required' => true],
            'tanggal_lahir' => ['label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true],
            'jenis_kelamin' => ['label' => 'Jenis Kelamin', 'type' => 'select', 'required' => true],
            'agama' => ['label' => 'Agama', 'type' => 'select', 'required' => true],
            'pekerjaan' => ['label' => 'Pekerjaan', 'type' => 'text', 'required' => true],
            'alamat' => ['label' => 'Alamat', 'type' => 'textarea', 'required' => true],

            // Letter-specific fields
            'keperluan' => ['label' => 'Keperluan', 'type' => 'textarea', 'required' => true],
            'alamat_domisili' => ['label' => 'Alamat Domisili', 'type' => 'textarea', 'required' => true],

            // Business fields
            'nama_usaha' => ['label' => 'Nama Usaha', 'type' => 'text', 'required' => true],
            'jenis_usaha' => ['label' => 'Jenis Usaha', 'type' => 'text', 'required' => true],
            'alamat_usaha' => ['label' => 'Alamat Usaha', 'type' => 'textarea', 'required' => true],

            // Education fields
            'sekolah' => ['label' => 'Asal Sekolah/Kampus', 'type' => 'text', 'required' => true],
            'nis_nim' => ['label' => 'NIS/NIM', 'type' => 'text', 'required' => true],
            'jurusan' => ['label' => 'Jurusan/Program Studi', 'type' => 'text', 'required' => true],
            'semester' => ['label' => 'Semester', 'type' => 'number', 'required' => true],
            'nama_beasiswa' => ['label' => 'Nama Beasiswa', 'type' => 'text', 'required' => true],
            'nama_ayah' => ['label' => 'Nama Ayah/Wali', 'type' => 'text', 'required' => true],

            // Family fields
            'nik_pasangan' => ['label' => 'NIK Pasangan', 'type' => 'text', 'required' => true],
            'nama_pasangan' => ['label' => 'Nama Pasangan', 'type' => 'text', 'required' => true],

            // Event fields
            'nama_kegiatan' => ['label' => 'Nama Kegiatan', 'type' => 'text', 'required' => true],
            'tanggal_kegiatan' => ['label' => 'Tanggal Kegiatan', 'type' => 'date', 'required' => true],
            'waktu_kegiatan' => ['label' => 'Waktu Kegiatan', 'type' => 'text', 'required' => true],
            'tempat_kegiatan' => ['label' => 'Tempat Kegiatan', 'type' => 'textarea', 'required' => true],
            'hiburan' => ['label' => 'Hiburan/Entertainment', 'type' => 'text', 'required' => true],

            // Financial fields
            'penghasilan' => ['label' => 'Penghasilan', 'type' => 'number', 'required' => true]
        ];

        // Map letter type IDs to their required fields (including personal info)
        $letterTypeFieldMap = [
            1 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'keperluan', 'alamat_domisili'], // SKD
            2 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nama_usaha', 'jenis_usaha', 'alamat_usaha', 'keperluan'], // SKU
            3 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nik_pasangan', 'nama_pasangan', 'keperluan'], // SPN
            4 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'penghasilan', 'keperluan'], // SKTM
            5 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'keperluan'], // SK
            6 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'keperluan'], // SKBM
            7 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'sekolah', 'nis_nim', 'jurusan', 'semester', 'nama_beasiswa', 'nama_ayah', 'keperluan'], // SRB
            8 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nama_usaha', 'jenis_usaha', 'alamat_usaha', 'keperluan'], // SIU
            9 => ['nama', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pekerjaan', 'alamat', 'nama_kegiatan', 'tanggal_kegiatan', 'waktu_kegiatan', 'tempat_kegiatan', 'hiburan', 'keperluan'], // SIK
        ];

        $result = [];

        // Add all required fields for this letter type (all manual entry)
        if (isset($letterTypeFieldMap[$letterTypeId])) {
            foreach ($letterTypeFieldMap[$letterTypeId] as $fieldName) {
                if (isset($allFields[$fieldName])) {
                    $result[$fieldName] = $allFields[$fieldName];
                }
            }
        }

        return $result;
    }
}
