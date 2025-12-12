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

        // Get all requests for history
        $all_requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code
             FROM letter_requests lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             WHERE lr.user_id = ?
             ORDER BY lr.created_at DESC",
            [$userId]
        );

        // Get available letter types
        $letter_types = $this->letterTypeModel->getAllActive();

        // Get request statistics for user
        $stats = $this->getUserStats($userId);

        // Pass data to view
        $title = 'Dashboard User';
        $extra_css = ['user-sidebar.css', 'user-dashboard.css'];
        $extra_js = ['user-navigation.js'];

        // Load dashboard view directly
        ob_start();
        include VIEWS_DIR . '/user/dashboard.php';
        $content = ob_get_clean();

        echo $content;
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
     * Process create request form
     */
    public function processCreateRequest() {
        requireAuth('user');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/requests/create');
            exit;
        }

        $userId = getCurrentUserId();
        $letterTypeId = (int) ($_POST['letter_type_id'] ?? 0);

        // Validate letter type
        $letterType = $this->letterTypeModel->findById($letterTypeId);
        if (!$letterType) {
            $_SESSION['error'] = 'Jenis surat tidak valid';
            header('Location: ' . BASE_URL . '/requests/create');
            exit;
        }

        // Get required fields
        $requiredFields = $this->letterTypeModel->getRequiredFields($letterTypeId);

        // Get and validate form data
        $requestData = [];
        $errors = [];

        foreach ($requiredFields as $field => $label) {
            $value = sanitize($_POST[$field] ?? '');
            if (empty($value)) {
                $errors[$field] = "Field '{$label}' wajib diisi";
            }
            $requestData[$field] = $value;
        }

        if (!empty($errors)) {
            $this->renderView('user/create_request', [
                'title' => 'Buat Permohonan Surat - ' . APP_NAME,
                'letterTypes' => $this->letterTypeModel->getAllActive(),
                'errors' => $errors,
                'old' => array_merge(['letter_type_id' => $letterTypeId], $requestData)
            ]);
            return;
        }

        // Create request
        $requestData = [
            'user_id' => $userId,
            'letter_type_id' => $letterTypeId,
            'request_data' => json_encode($requestData)
        ];

        if ($this->requestModel->create($requestData)) {
            $_SESSION['success'] = SUCCESS_REQUEST_CREATED;
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan saat membuat permohonan';
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

        $userId = getCurrentUserId();
        $request = $this->requestModel->findById($requestId);

        if (!$request || $request['user_id'] != $userId) {
            http_response_code(404);
            $this->renderView('errors/404', [
                'title' => 'Request Tidak Ditemukan - ' . APP_NAME
            ]);
            return;
        }

        // Parse request data
        $requestData = json_decode($request['request_data'], true) ?? [];

        $this->renderView('user/view_request', [
            'title' => 'Detail Permohonan - ' . APP_NAME,
            'request' => $request,
            'requestData' => $requestData
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
     * Get user statistics
     * @param int $userId
     * @return array
     */
    private function getUserStats($userId) {
        $stats = [
            'total' => 0,
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'completed' => 0
        ];

        $userRequests = fetchAll(
            "SELECT status, COUNT(*) as count FROM letter_requests WHERE user_id = ? GROUP BY status",
            [$userId]
        );

        foreach ($userRequests as $stat) {
            $stats[$stat['status']] = (int) $stat['count'];
            $stats['total'] += (int) $stat['count'];
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
        // Extract data to make variables available in view
        extract($data);

        // Include layout
        require VIEWS_DIR . '/layouts/user.php';
    }
}
