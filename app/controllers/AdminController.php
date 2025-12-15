<?php
/**
 * Admin Controller
 * ABIS - Aplikasi Desa Digital
 */

class AdminController {
    private $userModel;
    private $letterRequestModel;
    private $letterTypeModel;

    public function __construct() {
        $this->userModel = new User();
        $this->letterRequestModel = new LetterRequest();
        $this->letterTypeModel = new LetterType();
    }

    /**
     * Display admin dashboard
     */
    public function dashboard() {
        // Get comprehensive statistics
        $userStats = $this->userModel->getStats();
        $requestStats = $this->letterRequestModel->getStats();

        // Get today's statistics
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        // Requests today
        $pending_today = fetchValue(
            "SELECT COUNT(*) FROM letter_requests WHERE DATE(created_at) = ? AND status = ?",
            [$today, STATUS_PENDING]
        );

        $approved_today = fetchValue(
            "SELECT COUNT(*) FROM letter_requests WHERE DATE(approved_at) = ? AND status = ?",
            [$today, STATUS_APPROVED]
        );

        // Requests this month
        $this_month = fetchValue(
            "SELECT COUNT(*) FROM letter_requests WHERE DATE_FORMAT(created_at, '%Y-%m') = ?",
            [$thisMonth]
        );

        // Active users (users who made requests this month)
        $active_users = fetchValue(
            "SELECT COUNT(DISTINCT user_id) FROM letter_requests WHERE DATE_FORMAT(created_at, '%Y-%m') = ?",
            [$thisMonth]
        );

        // Average process time (mock data for now)
        $avg_process_time = '2.3';

        $stats = [
            'users' => [
                'total' => $userStats['total'],
                'admins' => $userStats['admins'],
                'active' => $active_users
            ],
            'requests' => [
                'total' => $requestStats['total'],
                'pending' => $requestStats['pending'],
                'approved' => $requestStats['approved'],
                'completed' => $requestStats['completed'],
                'rejected' => $requestStats['rejected'],
                'pending_today' => $pending_today,
                'approved_today' => $approved_today,
                'this_month' => $this_month
            ],
            'avg_process_time' => $avg_process_time
        ];

        // Get recent requests with full details
        $recent_requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, u.full_name as user_full_name, u.email as user_email
             FROM letter_requests lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             LEFT JOIN users u ON lr.user_id = u.id
             ORDER BY lr.created_at DESC
             LIMIT 5"
        );

        // Get recent activities (mock data for demonstration)
        $recent_activities = [
            [
                'title' => 'Surat Keterangan Domisili disetujui',
                'time' => date('d M Y, H:i', strtotime('-2 hours')),
                'icon' => 'fa-check-circle',
                'color' => 'green'
            ],
            [
                'title' => 'Pengguna baru terdaftar',
                'time' => date('d M Y, H:i', strtotime('-4 hours')),
                'icon' => 'fa-user-plus',
                'color' => 'blue'
            ],
            [
                'title' => 'Permintaan Surat Keterangan Tidak Mampu',
                'time' => date('d M Y, H:i', strtotime('-6 hours')),
                'icon' => 'fa-clock',
                'color' => 'yellow'
            ],
            [
                'title' => 'Surat Pengantar Nikah disetujui',
                'time' => date('d M Y, H:i', strtotime('-1 day')),
                'icon' => 'fa-check-circle',
                'color' => 'green'
            ],
            [
                'title' => '3 permintaan surat baru hari ini',
                'time' => date('d M Y, H:i', strtotime('-1 day')),
                'icon' => 'fa-file-alt',
                'color' => 'purple'
            ]
        ];

        // Get pending requests for quick actions
        $pending_requests = $this->letterRequestModel->getByStatus(STATUS_PENDING, 5);

        // Get recent users
        $recent_users = $this->userModel->getAll(1, 5)['users'];

        // Load dashboard view
        ob_start();
        include VIEWS_DIR . '/admin/dashboard.php';
        $content = ob_get_clean();

        // Load admin layout with sidebar
        include VIEWS_DIR . '/layouts/admin.php';
    }

    /**
     * Display users management
     */
    public function users() {
        requireAuth('admin');

        $search = $_GET['search'] ?? '';

        // Get all users without pagination for management
        $users_data = $this->userModel->getAllUsers($search);

        // Load view
        ob_start();
        include VIEWS_DIR . '/admin/users.php';
        $content = ob_get_clean();

        // Load admin layout
        include VIEWS_DIR . '/layouts/admin.php';
    }

    /**
     * Reset user password
     */
    public function resetPassword($userId) {
        requireAuth('admin');

        // Generate a new random password
        $newPassword = bin2hex(random_bytes(4)); // 8 character password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        if ($this->userModel->update($userId, ['password' => $hashedPassword])) {
            // Get user info for notification
            $user = $this->userModel->findById($userId);
            if ($user) {
                $_SESSION['success'] = "Password untuk {$user['full_name']} berhasil direset. Password baru: {$newPassword}";
            } else {
                $_SESSION['success'] = 'Password berhasil direset';
            }
        } else {
            $_SESSION['error'] = 'Gagal mereset password';
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    /**
     * Delete user
     */
    public function deleteUser($userId) {
        requireAuth('admin');

        if ($this->userModel->delete($userId)) {
            $_SESSION['success'] = 'User berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus user';
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    /**
     * Display requests management
     */
    public function requests() {
        requireAuth('admin');

        $page = $_GET['page'] ?? 1;
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';

        $filters = [];
        if (!empty($status)) $filters['status'] = $status;
        if (!empty($search)) $filters['search'] = $search;

        $requests_data = $this->letterRequestModel->getAll($page, ITEMS_PER_PAGE, $filters);

        // Load view
        ob_start();
        include VIEWS_DIR . '/admin/requests.php';
        $content = ob_get_clean();

        // Load admin layout
        include VIEWS_DIR . '/layouts/admin.php';
    }

    /**
     * Approve request
     */
    public function approveRequest($requestId) {
        requireAuth('admin');

        $adminId = getCurrentUserId();
        $notes = $_POST['notes'] ?? '';

        $success = $this->letterRequestModel->approve($requestId, $adminId, $notes);

        // If called via AJAX, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $success ? 'Permohonan berhasil disetujui' : 'Gagal menyetujui permohonan']);
            exit;
        }

        // Regular redirect
        if ($success) {
            $_SESSION['success'] = 'Permohonan berhasil disetujui';
        } else {
            $_SESSION['error'] = 'Gagal menyetujui permohonan';
        }

        header('Location: ' . BASE_URL . '/admin/requests');
        exit;
    }

    /**
     * Reject request
     */
    public function rejectRequest($requestId) {
        requireAuth('admin');

        $adminId = getCurrentUserId();
        $notes = $_POST['notes'] ?? '';

        $success = $this->letterRequestModel->reject($requestId, $adminId, $notes);

        // If called via AJAX, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $success ? 'Permohonan berhasil ditolak' : 'Gagal menolak permohonan']);
            exit;
        }

        // Regular redirect
        if ($success) {
            $_SESSION['success'] = 'Permohonan berhasil ditolak';
        } else {
            $_SESSION['error'] = 'Gagal menolak permohonan';
        }

        header('Location: ' . BASE_URL . '/admin/requests');
        exit;
    }

    /**
     * Create new surat pengantar
     */
    public function createSuratPengantar() {
        requireAuth('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/requests');
            exit;
        }

        // Get form data
        $namaLengkap = sanitize($_POST['nama_lengkap'] ?? '');
        $nik = sanitize($_POST['nik'] ?? '');
        $ttl = sanitize($_POST['ttl'] ?? '');
        $alamat = sanitize($_POST['alamat'] ?? '');
        $pekerjaan = sanitize($_POST['pekerjaan'] ?? '');
        $agama = sanitize($_POST['agama'] ?? '');
        $ditujukanKepada = sanitize($_POST['ditujukan_kepada'] ?? '');
        $keperluan = sanitize($_POST['keperluan'] ?? '');

        // Validate required fields
        if (empty($namaLengkap) || empty($nik) || empty($ttl) || empty($alamat) ||
            empty($pekerjaan) || empty($agama) || empty($ditujukanKepada) || empty($keperluan)) {
            $_SESSION['error'] = 'Semua field wajib diisi';
            header('Location: ' . BASE_URL . '/admin/requests');
            exit;
        }

        // Create request data
        $requestData = [
            'nama_lengkap' => $namaLengkap,
            'nik' => $nik,
            'ttl' => $ttl,
            'alamat' => $alamat,
            'pekerjaan' => $pekerjaan,
            'agama' => $agama,
            'ditujukan_kepada' => $ditujukanKepada,
            'keperluan' => $keperluan
        ];

        // Create letter request with admin as user (system-generated)
        $letterRequestData = [
            'user_id' => getCurrentUserId(), // Admin creating the request
            'letter_type_id' => 3, // SPN - Surat Pengantar Nikah
            'request_data' => json_encode($requestData),
            'status' => 'pending'
        ];

        if ($this->letterRequestModel->create($letterRequestData)) {
            $_SESSION['success'] = 'Surat pengantar berhasil dibuat';
        } else {
            $_SESSION['error'] = 'Gagal membuat surat pengantar';
        }

        header('Location: ' . BASE_URL . '/admin/requests');
        exit;
    }

    /**
     * Delete request
     */
    public function deleteRequest($requestId) {
        requireAuth('admin');

        $success = $this->letterRequestModel->delete($requestId);

        // If called via AJAX, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $success ? 'Permohonan berhasil dihapus' : 'Gagal menghapus permohonan']);
            exit;
        }

        // Regular redirect
        if ($success) {
            $_SESSION['success'] = 'Permohonan berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Gagal menghapus permohonan';
        }

        header('Location: ' . BASE_URL . '/admin/requests');
        exit;
    }

    /**
     * Download request file
     */
    public function downloadRequest($requestId) {
        requireAuth('admin');

        $request = $this->letterRequestModel->findById($requestId);

        if (!$request || empty($request['generated_file'])) {
            http_response_code(404);
            die('File tidak ditemukan');
        }

        $filePath = UPLOADS_DIR . '/' . $request['generated_file'];

        if (!file_exists($filePath)) {
            http_response_code(404);
            die('File tidak ditemukan');
        }

        // Force download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }

    /**
     * Display export data page
     */
    public function export() {
        $this->renderView('admin/export', [
            'title' => 'Export Data - ' . APP_NAME
        ]);
    }

    /**
     * Render view with layout
     */
    private function renderView($view, $data = []) {
        // Extract data to make variables available in view
        extract($data);

        // Start output buffering to capture view content
        ob_start();
        include VIEWS_DIR . '/' . $view . '.php';
        $content = ob_get_clean();

        // Include layout
        require VIEWS_DIR . '/layouts/admin.php';
    }
}
