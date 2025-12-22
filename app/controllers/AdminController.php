<?php
/**
 * Admin Controller
 * ABIS - Aplikasi Desa Digital
 */

require_once 'config/letter_constants.php';
require_once 'config/session.php';
require_once 'app/models/User.php';
require_once 'app/models/LetterRequest.php';
require_once 'app/models/LetterType.php';

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

        // Generate recent activities from actual database data
        $recent_activities = [];
        $activities_data = fetchAll(
            "SELECT lr.status, lr.created_at, lr.approved_at, lr.updated_at, lt.name as letter_type_name, u.full_name as user_name
             FROM letter_requests lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             LEFT JOIN users u ON lr.user_id = u.id
             ORDER BY lr.updated_at DESC
             LIMIT 5"
        );

        // Helper function to convert month name to Indonesian
        $bulanIndo = [
            'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr',
            'May' => 'Mei', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ags',
            'Sep' => 'Sep', 'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des'
        ];

        foreach ($activities_data as $activity) {
            // Format time with Indonesian month
            $timestamp = $activity['approved_at'] ?: $activity['updated_at'] ?: $activity['created_at'];
            $formatted_time = date('d M Y, H:i', strtotime($timestamp));
            $formatted_time = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $formatted_time);

            if ($activity['status'] === 'approved') {
                $recent_activities[] = [
                    'title' => 'Surat ' . $activity['letter_type_name'] . ' disetujui',
                    'time' => $formatted_time,
                    'icon' => 'check',
                    'color' => 'primary'
                ];
            } elseif ($activity['status'] === 'pending') {
                $recent_activities[] = [
                    'title' => 'Permintaan surat ' . $activity['letter_type_name'] . ' masuk',
                    'time' => date('d M Y, H:i', strtotime($activity['created_at'])),
                    'icon' => 'clock',
                    'color' => 'secondary'
                ];
            } elseif ($activity['status'] === 'rejected') {
                $recent_activities[] = [
                    'title' => 'Surat ' . $activity['letter_type_name'] . ' ditolak',
                    'time' => date('d M Y, H:i', strtotime($activity['updated_at'])),
                    'icon' => 'x',
                    'color' => 'accent'
                ];
            } elseif ($activity['status'] === 'completed') {
                $recent_activities[] = [
                    'title' => 'Surat ' . $activity['letter_type_name'] . ' selesai diproses',
                    'time' => date('d M Y, H:i', strtotime($activity['updated_at'])),
                    'icon' => 'check-circle',
                    'color' => 'primary'
                ];
            }
        }

        // Fallback if no activities found
        if (empty($recent_activities)) {
            $recent_activities = [
                [
                    'title' => 'Dashboard siap digunakan',
                    'time' => date('d M Y, H:i'),
                    'icon' => 'check',
                    'color' => 'primary'
                ]
            ];
        }

        // Get pending requests for quick actions
        $pending_requests = $this->letterRequestModel->getByStatus(STATUS_PENDING, 5);

        // Get recent users
        $recent_users = $this->userModel->getAll(1, 5)['users'];

        // Prepare data for view
        $data = [
            'stats' => $stats,
            'recent_requests' => $recent_requests,
            'recent_activities' => $recent_activities,
            'pending_requests' => $pending_requests,
            'recent_users' => $recent_users
        ];

        // Debug: Log data being passed to view
        error_log("AdminController::dashboard - Data keys: " . json_encode(array_keys($data)));
        error_log("AdminController::dashboard - recent_requests count: " . count($recent_requests ?? []));
        error_log("AdminController::dashboard - stats: " . json_encode($stats));

        // Extract data to make variables available in view
        extract($data);

        // Load dashboard view
        ob_start();
        $viewPath = VIEWS_DIR . '/admin/dashboard.php';
        if (!file_exists($viewPath)) {
            error_log("AdminController::dashboard - View file not found: {$viewPath}");
            die("View file not found: {$viewPath}");
        }
        include $viewPath;
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

        // Debug: Log data being passed to view
        error_log("AdminController::users - users_data keys: " . json_encode(array_keys($users_data)));
        error_log("AdminController::users - users count: " . count($users_data['users'] ?? []));

        // Prepare data for view
        $data = [
            'title' => 'Manajemen Pengguna',
            'extra_css' => ['admin-dashboard.css'],
            'extra_js' => ['admin-dashboard.js'],
            'users_data' => $users_data,
            'search' => $search
        ];

        // Extract data to make variables available in view
        extract($data);

        // Load view
        ob_start();
        $viewPath = VIEWS_DIR . '/admin/users.php';
        if (!file_exists($viewPath)) {
            error_log("AdminController::users - View file not found: {$viewPath}");
            die("View file not found: {$viewPath}");
        }
        include $viewPath;
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
     * Display letter requests management (approve/reject)
     */
    public function letterRequests() {
        requireAuth('admin');

        $page = $_GET['page'] ?? 1;
        $status = $_GET['status'] ?? '';

        $filters = [];
        if (!empty($status)) $filters['status'] = $status;

        // For admin letter requests page, show all requests without pagination to see everything at once
        $requests_data = $this->letterRequestModel->getAll($page, 1000, $filters); // Large limit to show all

        // Prepare data for view
        $data = [
            'title' => 'Pengajuan Surat',
            'extra_css' => ['admin-dashboard.css'],
            'extra_js' => ['admin-dashboard.js'],
            'requests_data' => $requests_data,
            'status' => $status
        ];

        // Debug: Log data being passed to view
        error_log("AdminController::letterRequests - Data keys: " . json_encode(array_keys($data)));
        error_log("AdminController::letterRequests - requests_data count: " . count($requests_data['requests'] ?? []));

        // Extract data to make variables available in view
        extract($data);

        // Load view
        ob_start();
        $viewPath = VIEWS_DIR . '/admin/letter-requests.php';
        if (!file_exists($viewPath)) {
            error_log("AdminController::letterRequests - View file not found: {$viewPath}");
            die("View file not found: {$viewPath}");
        }
        include $viewPath;
        $content = ob_get_clean();

        // Load admin layout
        include VIEWS_DIR . '/layouts/admin.php';
    }

    /**
     * Display requests management (export)
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
     * Export requests to Excel
     */
    public function exportExcel() {
        try {
            error_log("Excel Export: Starting export process");

            // IMPORTANT: Clean output buffer to prevent corruption
            while (ob_get_level()) {
                ob_end_clean();
            }

            requireAuth('admin');
            error_log("Excel Export: Authentication passed");

            // Get filter parameters
            $dari_tanggal = $_GET['dari_tanggal'] ?? '';
            $sampai_tanggal = $_GET['sampai_tanggal'] ?? '';

            error_log("Excel Export: Parameters - dari_tanggal: $dari_tanggal, sampai_tanggal: $sampai_tanggal");

            // Build filters
            $filters = [];
            if (!empty($dari_tanggal)) $filters['dari_tanggal'] = $dari_tanggal;
            if (!empty($sampai_tanggal)) $filters['sampai_tanggal'] = $sampai_tanggal;

            // Get all requests without pagination
            $requests = $this->letterRequestModel->getAllForExport($filters);

            error_log("Excel Export: Found " . count($requests) . " records to export");

            if (empty($requests)) {
                error_log("Excel Export: No data found, redirecting to export page");
                header('Location: ' . BASE_URL . '/admin/export');
                exit;
            }

            // Validate data before creating Excel
            if (!is_array($requests) || count($requests) === 0) {
                error_log("Excel Export: Invalid data format");
                $_SESSION['error'] = 'Data tidak valid untuk export';
                header('Location: ' . BASE_URL . '/admin/export');
                exit;
            }

            // Create Excel file
            $this->createExcelFile($requests);

        } catch (Exception $e) {
            error_log("Excel Export: Fatal error in exportExcel: " . $e->getMessage());
            error_log("Excel Export: Stack trace: " . $e->getTraceAsString());

            // Clean output and redirect with error
            while (ob_get_level()) {
                ob_end_clean();
            }

            $_SESSION['error'] = 'Terjadi kesalahan saat membuat file Excel. Silakan coba lagi.';
            header('Location: ' . BASE_URL . '/admin/export');
            exit;
        }
    }

    /**
     * Generate and download PDF for approved request
     */
    public function downloadRequest($requestId) {
        try {
            requireAuth('admin');

            $request = $this->letterRequestModel->findById($requestId);

            if (!$request) {
                $this->sendErrorResponse('Pengajuan surat tidak ditemukan.', 404);
            }

            if ($request['status'] !== 'approved') {
                $this->sendErrorResponse('Surat hanya dapat didownload jika sudah disetujui.', 403);
            }

            // Check if PDF already generated
            if (empty($request['generated_file'])) {
                // Generate PDF if not exists
                $this->generateLetterPDF($requestId);
                // Re-fetch request data
                $request = $this->letterRequestModel->findById($requestId);
            }

            if (empty($request['generated_file'])) {
                $this->sendErrorResponse('Gagal membuat file PDF.', 500);
            }

            // Download the file
            require_once 'utils/PDFGenerator.php';
            $pdfGenerator = new PDFGenerator();
            $filePath = $pdfGenerator->getFilePath($request['generated_file']);

            if (!$pdfGenerator->fileExists($request['generated_file'])) {
                $this->sendErrorResponse('File PDF tidak ditemukan.', 404);
            }

            // Check if this is an AJAX request
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

            if ($isAjax) {
                // For AJAX requests, send file as response
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
                header('Content-Length: ' . filesize($filePath));
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');

                readfile($filePath);
                exit;
            } else {
                // For regular requests, force download
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
                header('Content-Length: ' . filesize($filePath));
                header('Cache-Control: private, max-age=0, must-revalidate');
                header('Pragma: public');

                readfile($filePath);
                exit;
            }

        } catch (Exception $e) {
            error_log('Download PDF error: ' . $e->getMessage());
            $this->sendErrorResponse('Terjadi kesalahan saat mengunduh PDF.', 500);
        }
    }

    /**
     * Send error response for AJAX or redirect for regular requests
     */
    private function sendErrorResponse($message, $statusCode = 500) {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($isAjax) {
            http_response_code($statusCode);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $message]);
        } else {
            $_SESSION['error'] = $message;
            header('Location: ' . BASE_URL . '/admin/letter-requests');
        }
        exit;
    }

    /**
     * Generate PDF document
     */
    private function generatePDF($request) {
        // Set headers for PDF download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="surat_' . $request['letter_type_code'] . '_' . date('Y-m-d') . '.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        // Generate PDF content (HTML to PDF)
        $pdfContent = $this->generatePDFContent($request);

        // For now, we'll create a simple HTML-based PDF
        // In production, you might want to use libraries like TCPDF, FPDF, or DomPDF
        echo $pdfContent;
        exit;
    }

    /**
     * Generate PDF content as HTML
     */
    private function generatePDFContent($request) {
        $letterType = $request['letter_type_name'];
        $userName = $request['user_full_name'];
        $nik = $request['nik'] ?? 'N/A';
        $requestDate = date('d F Y', strtotime($request['created_at']));
        $approvedDate = date('d F Y');

        // Different letter templates based on type
        switch(strtoupper($request['letter_type_code'])) {
            case 'SK':
                return $this->generateGenericLetter($request); // Surat Keterangan Umum
            case 'SKD':
                return $this->generateDomicileLetter($request); // Surat Keterangan Domisili
            case 'SKTM':
                return $this->generatePoorLetter($request); // Surat Keterangan Tidak Mampu
            case 'SKU':
                return $this->generateBusinessLetter($request); // Surat Keterangan Usaha
            case 'SKBM':
                return $this->generateMarriageLetter($request); // Surat Keterangan Belum Menikah
            case 'SRB':
                return $this->generateScholarshipLetter($request); // Surat Rekomendasi Beasiswa
            case 'SIU':
                return $this->generateBusinessPermitLetter($request); // Surat Izin Usaha
            case 'SIK':
                return $this->generateEventPermitLetter($request); // Surat Izin Kegiatan
            case 'SPN':
                return $this->generateMarriageCertificateLetter($request); // Surat Pengantar Nikah
            default:
                return $this->generateGenericLetter($request);
        }
    }

    /**
     * Generate KTP Letter
     */
    private function generateKTPLetter($request) {
        $content = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Surat Keterangan KTP - {$request['user_full_name']}</title>
            <style>
                body { font-family: 'Times New Roman', serif; margin: 0; padding: 50px; line-height: 1.6; }
                .header { text-align: center; margin-bottom: 30px; }
                .logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                .address { font-size: 14px; margin-bottom: 20px; }
                .title { font-size: 18px; font-weight: bold; text-decoration: underline; margin: 30px 0; }
                .content { text-align: justify; margin: 20px 0; }
                .signature { margin-top: 50px; text-align: right; }
                .signature-line { margin-top: 80px; border-top: 1px solid black; width: 200px; display: inline-block; }
                .number { position: absolute; top: 50px; right: 50px; }
            </style>
        </head>
        <body>
            <div class='number'>
                <strong>No: {$request['id']}/SK-KTP/" . date('Y') . "</strong>
            </div>

            <div class='header'>
                <div class='logo'>PEMERINTAH DESA PENGLIPURAN</div>
                <div class='address'>Jl. Raya Penglipuran, Kabupaten Gianyar, Bali 80552</div>
            </div>

            <div class='title'>SURAT KETERANGAN KTP</div>

            <div class='content'>
                <p>Yang bertanda tangan di bawah ini Kepala Desa Penglipuran, Kecamatan Blahbatuh, Kabupaten Gianyar, Provinsi Bali, menerangkan bahwa:</p>

                <table style='margin: 20px 40px;'>
                    <tr><td width='150'>Nama</td><td>: {$request['user_full_name']}</td></tr>
                    <tr><td>NIK</td><td>: {$request['nik']}</td></tr>
                    <tr><td>Tempat/Tgl Lahir</td><td>: - / -</td></tr>
                    <tr><td>Jenis Kelamin</td><td>: -</td></tr>
                    <tr><td>Pekerjaan</td><td>: -</td></tr>
                    <tr><td>Alamat</td><td>: Desa Penglipuran</td></tr>
                </table>

                <p>Berdasarkan data yang ada, yang bersangkutan <strong>BELUM MEMILIKI KTP</strong> dan sedang dalam proses pembuatan KTP.</p>

                <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
            </div>

            <div class='signature'>
                <p>Penglipuran, " . date('d F Y') . "</p>
                <p>Kepala Desa Penglipuran</p>
                <div class='signature-line'></div>
                <p><strong>I Gusti Agung Gede</strong></p>
            </div>
        </body>
        </html>";
        return $content;
    }

    /**
     * Generate Domicile Letter
     */
    private function generateDomicileLetter($request) {
        $content = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Surat Keterangan Domisili - {$request['user_full_name']}</title>
            <style>
                body { font-family: 'Times New Roman', serif; margin: 0; padding: 50px; line-height: 1.6; }
                .header { text-align: center; margin-bottom: 30px; }
                .logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                .address { font-size: 14px; margin-bottom: 20px; }
                .title { font-size: 18px; font-weight: bold; text-decoration: underline; margin: 30px 0; }
                .content { text-align: justify; margin: 20px 0; }
                .signature { margin-top: 50px; text-align: right; }
                .signature-line { margin-top: 80px; border-top: 1px solid black; width: 200px; display: inline-block; }
                .number { position: absolute; top: 50px; right: 50px; }
            </style>
        </head>
        <body>
            <div class='number'>
                <strong>No: {$request['id']}/SK-DOM/" . date('Y') . "</strong>
            </div>

            <div class='header'>
                <div class='logo'>PEMERINTAH DESA PENGLIPURAN</div>
                <div class='address'>Jl. Raya Penglipuran, Kabupaten Gianyar, Bali 80552</div>
            </div>

            <div class='title'>SURAT KETERANGAN DOMISILI</div>

            <div class='content'>
                <p>Yang bertanda tangan di bawah ini Kepala Desa Penglipuran, Kecamatan Blahbatuh, Kabupaten Gianyar, Provinsi Bali, menerangkan bahwa:</p>

                <table style='margin: 20px 40px;'>
                    <tr><td width='150'>Nama</td><td>: {$request['user_full_name']}</td></tr>
                    <tr><td>NIK</td><td>: {$request['nik']}</td></tr>
                    <tr><td>Tempat/Tgl Lahir</td><td>: - / -</td></tr>
                    <tr><td>Jenis Kelamin</td><td>: -</td></tr>
                    <tr><td>Pekerjaan</td><td>: -</td></tr>
                    <tr><td>Alamat</td><td>: Desa Penglipuran</td></tr>
                </table>

                <p>Berdasarkan data yang ada, yang bersangkutan benar-benar <strong>BERDOMISILI</strong> di Desa Penglipuran, Kecamatan Blahbatuh, Kabupaten Gianyar, Provinsi Bali.</p>

                <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
            </div>

            <div class='signature'>
                <p>Penglipuran, " . date('d F Y') . "</p>
                <p>Kepala Desa Penglipuran</p>
                <div class='signature-line'></div>
                <p><strong>I Gusti Agung Gede</strong></p>
            </div>
        </body>
        </html>";
        return $content;
    }

    /**
     * Generate Poor Letter
     */
    private function generatePoorLetter($request) {
        $content = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Surat Keterangan Tidak Mampu - {$request['user_full_name']}</title>
            <style>
                body { font-family: 'Times New Roman', serif; margin: 0; padding: 50px; line-height: 1.6; }
                .header { text-align: center; margin-bottom: 30px; }
                .logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                .address { font-size: 14px; margin-bottom: 20px; }
                .title { font-size: 18px; font-weight: bold; text-decoration: underline; margin: 30px 0; }
                .content { text-align: justify; margin: 20px 0; }
                .signature { margin-top: 50px; text-align: right; }
                .signature-line { margin-top: 80px; border-top: 1px solid black; width: 200px; display: inline-block; }
                .number { position: absolute; top: 50px; right: 50px; }
            </style>
        </head>
        <body>
            <div class='number'>
                <strong>No: {$request['id']}/SK-TM/" . date('Y') . "</strong>
            </div>

            <div class='header'>
                <div class='logo'>PEMERINTAH DESA PENGLIPURAN</div>
                <div class='address'>Jl. Raya Penglipuran, Kabupaten Gianyar, Bali 80552</div>
            </div>

            <div class='title'>SURAT KETERANGAN TIDAK MAMPU</div>

            <div class='content'>
                <p>Yang bertanda tangan di bawah ini Kepala Desa Penglipuran, Kecamatan Blahbatuh, Kabupaten Gianyar, Provinsi Bali, menerangkan bahwa:</p>

                <table style='margin: 20px 40px;'>
                    <tr><td width='150'>Nama</td><td>: {$request['user_full_name']}</td></tr>
                    <tr><td>NIK</td><td>: {$request['nik']}</td></tr>
                    <tr><td>Tempat/Tgl Lahir</td><td>: - / -</td></tr>
                    <tr><td>Jenis Kelamin</td><td>: -</td></tr>
                    <tr><td>Pekerjaan</td><td>: -</td></tr>
                    <tr><td>Alamat</td><td>: Desa Penglipuran</td></tr>
                </table>

                <p>Berdasarkan data dan pengamatan di lapangan, yang bersangkutan termasuk dalam kategori <strong>KELUARGA TIDAK MAMPU</strong> secara ekonomi.</p>

                <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
            </div>

            <div class='signature'>
                <p>Penglipuran, " . date('d F Y') . "</p>
                <p>Kepala Desa Penglipuran</p>
                <div class='signature-line'></div>
                <p><strong>I Gusti Agung Gede</strong></p>
            </div>
        </body>
        </html>";
        return $content;
    }

    /**
     * Generate Generic Letter
     */
    private function generateGenericLetter($request) {
        $content = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>{$request['letter_type_name']} - {$request['user_full_name']}</title>
            <style>
                body { font-family: 'Times New Roman', serif; margin: 0; padding: 50px; line-height: 1.6; }
                .header { text-align: center; margin-bottom: 30px; }
                .logo { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
                .address { font-size: 14px; margin-bottom: 20px; }
                .title { font-size: 18px; font-weight: bold; text-decoration: underline; margin: 30px 0; }
                .content { text-align: justify; margin: 20px 0; }
                .signature { margin-top: 50px; text-align: right; }
                .signature-line { margin-top: 80px; border-top: 1px solid black; width: 200px; display: inline-block; }
                .number { position: absolute; top: 50px; right: 50px; }
            </style>
        </head>
        <body>
            <div class='number'>
                <strong>No: {$request['id']}/{$request['letter_type_code']}/" . date('Y') . "</strong>
            </div>

            <div class='header'>
                <div class='logo'>PEMERINTAH DESA PENGLIPURAN</div>
                <div class='address'>Jl. Raya Penglipuran, Kabupaten Gianyar, Bali 80552</div>
            </div>

            <div class='title'>{$request['letter_type_name']}</div>

            <div class='content'>
                <p>Yang bertanda tangan di bawah ini Kepala Desa Penglipuran, Kecamatan Blahbatuh, Kabupaten Gianyar, Provinsi Bali, menerangkan bahwa:</p>

                <table style='margin: 20px 40px;'>
                    <tr><td width='150'>Nama</td><td>: {$request['user_full_name']}</td></tr>
                    <tr><td>NIK</td><td>: {$request['nik']}</td></tr>
                    <tr><td>Alamat</td><td>: Desa Penglipuran</td></tr>
                </table>

                <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
            </div>

            <div class='signature'>
                <p>Penglipuran, " . date('d F Y') . "</p>
                <p>Kepala Desa Penglipuran</p>
                <div class='signature-line'></div>
                <p><strong>I Gusti Agung Gede</strong></p>
            </div>
        </body>
        </html>";
        return $content;
    }

    /**
     * Create and download Excel file using PhpSpreadsheet
     */
    private function createExcelFile($requests) {
        try {
            // Clean any existing output buffers completely
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Ensure no output buffers are active
            if (ob_get_level() > 0) {
                ob_clean();
            }

            require_once 'vendor/autoload.php';

            error_log("Excel Export: Creating spreadsheet with " . count($requests) . " records");

            // Create new Spreadsheet object
            $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set UTF-8 encoding
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator('ABIS - Aplikasi Desa Digital')
                ->setLastModifiedBy('ABIS System')
                ->setTitle('Export Data Surat')
                ->setSubject('Data Pengajuan Surat')
                ->setDescription('Export data pengajuan surat dari sistem ABIS');

            // Set headers
            $headers = ['No', 'Tanggal', 'Jenis Surat', 'Nama Pemohon', 'NIK', 'Status', 'Catatan Admin'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $sheet->getColumnDimension($col)->setAutoSize(true);
                $col++;
            }

            // Style the header row
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'], // Indigo color
                ],
            ];
            $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

            // Add data rows
            $row = 2;
            $no = 1;
            foreach ($requests as $request) {
                // Sanitize data to prevent corruption
                $tanggal = isset($request['created_at']) ? date('d/m/Y', strtotime($request['created_at'])) : '';
                $jenisSurat = htmlspecialchars_decode($request['letter_type_name'] ?? '', ENT_QUOTES);
                $namaPemohon = htmlspecialchars_decode($request['user_full_name'] ?? '', ENT_QUOTES);
                $nik = $request['user_nik'] ?? '';
                $status = ucfirst($request['status'] ?? 'pending');
                $catatan = htmlspecialchars_decode($request['admin_notes'] ?? '', ENT_QUOTES);

                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $tanggal);
                $sheet->setCellValue('C' . $row, $jenisSurat);
                $sheet->setCellValue('D' . $row, $namaPemohon);
                $sheet->setCellValue('E' . $row, $nik);
                $sheet->setCellValue('F' . $row, $status);
                $sheet->setCellValue('G' . $row, $catatan);

                $row++;
            }

            // Set filename
            $filename = 'data_surat_' . date('Y-m-d_H-i-s') . '.xlsx';

            error_log("Excel Export: Generated filename: $filename");

            // Final cleanup - ensure no output buffers remain and no previous output
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Check if any output has been sent already
            if (headers_sent($file, $line)) {
                error_log("Excel Export: Headers already sent in $file:$line - cannot send Excel file");
                throw new Exception('Cannot send Excel file - output already started');
            }

            // Set headers for Excel download (minimal required headers)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            header('Content-Transfer-Encoding: binary');

            // CRITICAL: Clean any remaining output buffer to prevent corruption
            if (ob_get_length()) {
                ob_clean();
            }

            // Create writer and save directly to output without buffering
            $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');

            // End the script immediately to prevent any additional output
            exit;

        } catch (Exception $e) {
            error_log("Excel Export Error: " . $e->getMessage());
            error_log("Excel Export Stack Trace: " . $e->getTraceAsString());

            // Clean output and redirect with error
            while (ob_get_level()) {
                ob_end_clean();
            }

            $_SESSION['error'] = 'Gagal membuat file Excel: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/export');
            exit;
        }
    }

    /**
     * Fallback: Create and download Excel file as HTML table
     */
    private function createExcelFileHTML($requests) {
        try {
            // Clean any existing output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }

            error_log("Excel Export Fallback: Using HTML table method for " . count($requests) . " records");

            // Set headers for Excel download
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="data_surat_' . date('Y-m-d_H-i-s') . '.xls"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Expires: 0');

            // Create Excel content
            $excelContent = $this->generateExcelContent($requests);

            echo $excelContent;
            exit;
        } catch (Exception $e) {
            error_log("Excel Export HTML Fallback Error: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal membuat file Excel: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '/admin/export');
            exit;
        }
    }

    /**
     * Generate Excel content as HTML table
     */
    private function generateExcelContent($requests) {
        $content = "<table border='1'>";
        $content .= "<tr>";
        $content .= "<th>No</th>";
        $content .= "<th>Tanggal</th>";
        $content .= "<th>Jenis Surat</th>";
        $content .= "<th>Nama Pemohon</th>";
        $content .= "<th>NIK</th>";
        $content .= "<th>Status</th>";
        $content .= "<th>Catatan</th>";
        $content .= "</tr>";

        $no = 1;
        foreach ($requests as $request) {
            $content .= "<tr>";
            $content .= "<td>" . $no++ . "</td>";
            $content .= "<td>" . date('d/m/Y', strtotime($request['created_at'])) . "</td>";
            $content .= "<td>" . htmlspecialchars($request['letter_type_name']) . "</td>";
            $content .= "<td>" . htmlspecialchars($request['full_name']) . "</td>";
            $content .= "<td>" . htmlspecialchars($request['nik']) . "</td>";
            $content .= "<td>" . ucfirst($request['status']) . "</td>";
            $content .= "<td>" . htmlspecialchars($request['notes'] ?? '') . "</td>";
            $content .= "</tr>";
        }

        $content .= "</table>";
        return $content;
    }

    /**
     * Approve request
     */
    public function approveRequest($requestId) {
        try {
            requireAuth('admin');

            $adminId = getCurrentUserId();
            $notes = $_POST['notes'] ?? '';

            $success = $this->letterRequestModel->approve($requestId, $adminId, $notes);

            // Auto-generate PDF if approval successful
            if ($success) {
                // Generate letter number first
                $request = $this->letterRequestModel->findById($requestId);
                if ($request) {
                    $letterNumber = $this->generateLetterNumber($request);
                    $this->letterRequestModel->update($requestId, ['letter_number' => $letterNumber]);
                }

                // Generate PDF
                $this->generateLetterPDF($requestId);

                // Send Telegram notification with PDF
                try {
                    require_once 'utils/TelegramBot.php';
                    $telegramBot = new TelegramBot();
                    $adminName = $this->userModel->findById($adminId)['full_name'] ?? 'Admin';

                    // Get request data with user info
                    $requestData = $this->letterRequestModel->findById($requestId);
                    if ($requestData) {
                        // Get PDF file path if available
                        $pdfFilePath = null;
                        if (!empty($requestData['generated_file'])) {
                            $pdfFilePath = UPLOADS_DIR . '/' . $requestData['generated_file'];
                        }

                        $telegramBot->sendApprovalNotification($requestData, $adminName, $pdfFilePath);
                    }
                } catch (Exception $e) {
                    error_log('Telegram notification error (approval): ' . $e->getMessage());
                    // Don't fail the approval process if Telegram fails
                }
            }

            $message = $success ? 'Permohonan berhasil disetujui' : 'Gagal menyetujui permohonan';

        } catch (Exception $e) {
            $success = false;
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
            error_log('Approve request error: ' . $e->getMessage());
        }

        // If called via AJAX, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        // Regular redirect
        if ($success) {
            $_SESSION['success'] = 'Permohonan berhasil disetujui dan surat telah dibuat';
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
        try {
            requireAuth('admin');

            $adminId = getCurrentUserId();
            $notes = $_POST['notes'] ?? '';

            $success = $this->letterRequestModel->reject($requestId, $adminId, $notes);

            // Send Telegram notification for rejection
            if ($success) {
                try {
                    require_once 'utils/TelegramBot.php';
                    $telegramBot = new TelegramBot();
                    $adminName = $this->userModel->findById($adminId)['full_name'] ?? 'Admin';

                    // Get request data with user info
                    $requestData = $this->letterRequestModel->findById($requestId);
                    if ($requestData) {
                        $telegramBot->sendRejectionNotification($requestData, $adminName, $notes);
                    }
                } catch (Exception $e) {
                    error_log('Telegram notification error (rejection): ' . $e->getMessage());
                    // Don't fail the rejection process if Telegram fails
                }
            }

            $message = $success ? 'Permohonan berhasil ditolak' : 'Gagal menolak permohonan';

        } catch (Exception $e) {
            $success = false;
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
            error_log('Reject request error: ' . $e->getMessage());
        }

        // If called via AJAX, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
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
     * Telegram Bot Settings
     */
    public function telegramSettings() {
        requireAuth('admin');

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['test_bot'])) {
                // Test bot connection
                try {
                    require_once 'utils/TelegramBot.php';
                    $telegramBot = new TelegramBot();
                    $result = $telegramBot->testConnection();

                    if ($result['ok']) {
                        $success = '✅ Koneksi bot berhasil! Bot dapat menerima pesan.';
                    } else {
                        $error = '❌ Koneksi bot gagal: ' . ($result['description'] ?? 'Unknown error');
                    }
                } catch (Exception $e) {
                    $error = '❌ Error: ' . $e->getMessage();
                }
            } elseif (isset($_POST['update_settings'])) {
                // Update bot settings
                $botToken = sanitize($_POST['bot_token'] ?? '');
                $testChatId = sanitize($_POST['test_chat_id'] ?? '');
                $notificationsEnabled = isset($_POST['notifications_enabled']) ? 'true' : 'false';

                try {
                    // Update config file
                    $configFile = 'config/telegram.php';
                    $configContent = file_get_contents($configFile);

                    // Update bot token
                    $configContent = preg_replace(
                        "/define\('TELEGRAM_BOT_TOKEN', '[^']*'\);/",
                        "define('TELEGRAM_BOT_TOKEN', '" . addslashes($botToken) . "');",
                        $configContent
                    );

                    // Update test chat ID
                    $configContent = preg_replace(
                        "/define\('TELEGRAM_TEST_CHAT_ID', '[^']*'\);/",
                        "define('TELEGRAM_TEST_CHAT_ID', '" . addslashes($testChatId) . "');",
                        $configContent
                    );

                    // Update notifications enabled
                    $configContent = preg_replace(
                        "/define\('TELEGRAM_NOTIFICATIONS_ENABLED', (true|false)\);/",
                        "define('TELEGRAM_NOTIFICATIONS_ENABLED', " . $notificationsEnabled . ");",
                        $configContent
                    );

                    file_put_contents($configFile, $configContent);
                    $success = '✅ Pengaturan bot berhasil diperbarui!';

                } catch (Exception $e) {
                    $error = '❌ Gagal memperbarui pengaturan: ' . $e->getMessage();
                }
            }
        }

        // Load current settings
        require_once 'config/telegram.php';
        $currentSettings = [
            'bot_token' => TELEGRAM_BOT_TOKEN,
            'test_chat_id' => TELEGRAM_TEST_CHAT_ID,
            'notifications_enabled' => TELEGRAM_NOTIFICATIONS_ENABLED
        ];

        // Get bot info if token is set
        $botInfo = null;
        if (!empty($currentSettings['bot_token']) && $currentSettings['bot_token'] !== 'YOUR_BOT_TOKEN_HERE') {
            try {
                require_once 'utils/TelegramBot.php';
                $telegramBot = new TelegramBot();
                $botInfo = $telegramBot->testConnection();
            } catch (Exception $e) {
                // Ignore errors
            }
        }

        // Get recent log entries
        $logFile = TELEGRAM_LOG_FILE;
        $recentLogs = [];
        if (file_exists($logFile)) {
            $logs = array_slice(file($logFile), -20); // Get last 20 lines
            $recentLogs = array_reverse($logs); // Show newest first
        }

        // Prepare data for view
        $data = [
            'title' => 'Pengaturan Telegram Bot',
            'extra_css' => ['admin-dashboard.css'],
            'extra_js' => ['admin-dashboard.js'],
            'currentSettings' => $currentSettings,
            'botInfo' => $botInfo,
            'recentLogs' => $recentLogs,
            'error' => $error,
            'success' => $success
        ];

        // Debug: Log data being passed to view
        error_log("AdminController::telegramSettings - Data keys: " . json_encode(array_keys($data)));

        // Extract data to make variables available in view
        extract($data);

        // Load view
        ob_start();
        $viewPath = VIEWS_DIR . '/admin/telegram-settings.php';
        if (!file_exists($viewPath)) {
            error_log("AdminController::telegramSettings - View file not found: {$viewPath}");
            die("View file not found: {$viewPath}");
        }
        include $viewPath;
        $content = ob_get_clean();

        // Load admin layout
        include VIEWS_DIR . '/layouts/admin.php';
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
        $formData = array_merge([
            'user_id' => getCurrentUserId(), // Admin creating the request
            'letter_type_id' => 3, // SPN - Surat Pengantar Nikah
            'status' => 'pending'
        ], $requestData);

        if ($this->letterRequestModel->createWithDataSeparation($formData)) {
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
     * Download request file (existing file)
     */
    public function downloadRequestFile($requestId) {
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
     * API endpoint to get filtered letter requests for export page
     */
    public function getExportData() {
        try {
            requireAuth('admin');

            // Get filter parameters
            $dari_tanggal = $_GET['dari_tanggal'] ?? '';
            $sampai_tanggal = $_GET['sampai_tanggal'] ?? '';

            // Build filters
            $filters = [];
            if (!empty($dari_tanggal)) $filters['dari_tanggal'] = $dari_tanggal;
            if (!empty($sampai_tanggal)) $filters['sampai_tanggal'] = $sampai_tanggal;

            // Get filtered requests
            $requests = $this->letterRequestModel->getAllForExport($filters);

            // Format data for frontend
            $formattedData = array_map(function($request) {
                return [
                    'id' => $request['id'],
                    'tanggal' => date('d/m/Y', strtotime($request['created_at'])),
                    'jenisSurat' => $request['letter_type_name'],
                    'pemohon' => $request['user_full_name'],
                    'nik' => $request['nik'] ?? $request['user_nik'] ?? '-',
                    'status' => $request['status'],
                    'jenisColor' => $this->getStatusColor($request['status']),
                    'letter_type_code' => $request['letter_type_code']
                ];
            }, $requests);

            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $formattedData,
                'total' => count($formattedData)
            ]);

        } catch (Exception $e) {
            // Return error response
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => [],
                'total' => 0
            ]);
        }
        exit;
    }

    /**
     * Get color class for status
     */
    private function getStatusColor($status) {
        switch ($status) {
            case 'approved':
                return 'green-500';
            case 'pending':
                return 'primary';
            case 'rejected':
                return 'red-500';
            default:
                return 'gray-500';
        }
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

    /**
     * Generate PDF letter for approved request
     * @param int $requestId
     * @return bool
     */
    private function generateLetterPDF($requestId) {
        try {
            require_once 'utils/PDFGenerator.php';

            // Get request data with related information
            $request = $this->letterRequestModel->findById($requestId);
            if (!$request) {
                error_log("Request not found for PDF generation: {$requestId}");
                return false;
            }

            // Get letter type info
            $letterType = $this->letterTypeModel->findById($request['letter_type_id']);
            if (!$letterType) {
                error_log("Letter type not found for request: {$requestId}");
                return false;
            }

            $pdfData = $this->preparePDFData($request, $letterType);

            $pdfGenerator = new PDFGenerator();
            $filename = $this->generatePDFFilename($letterType, $request);
            $templateName = $this->getTemplateName($letterType['name']);

            $generatedFile = $pdfGenerator->generateFromTemplate($templateName, $pdfData, $filename);

            if ($generatedFile) {
                // Update request with generated file path
                $this->letterRequestModel->updateGeneratedFile($requestId, $generatedFile);
                return true;
            }

            return false;

        } catch (Exception $e) {
            error_log("PDF Generation Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Prepare comprehensive data for PDF template
     * @param array $request
     * @param array $letterType
     * @return array
     */
    private function preparePDFData($request, $letterType) {
        $requestData = [];
        $additionalData = [];

        if (!empty($request['request_data'])) {
            $requestData = json_decode($request['request_data'], true) ?? [];
        }

        if (!empty($request['additional_data'])) {
            $additionalData = json_decode($request['additional_data'], true) ?? [];
        }

        $flattenedAdditionalData = [];
        foreach ($additionalData as $category => $categoryData) {
            if (is_array($categoryData)) {
                $flattenedAdditionalData = array_merge($flattenedAdditionalData, $categoryData);
            }
        }

        $userProfile = $this->getUserProfileData($request['user_id']);

        // Prioritize user input data over profile data
        // Order: user profile (fallback) -> request data -> form input data -> additional data
        $mergedRequest = array_merge(
            $userProfile,            // Fallback data from user profile
            $request,                // Request metadata
            $requestData,            // Primary user input data from form
            $flattenedAdditionalData // Additional form data
        );

        $baseData = $this->getBasePDFData($mergedRequest, $letterType);
        $templateSpecificData = $this->getTemplateSpecificData($mergedRequest);
        
        return array_merge($baseData, $templateSpecificData);
        
    }

    /**
     * Calculate age from birth date
     * @param string $birthDate
     * @return int
     */
    private function calculateAge($birthDate): int {
        if (empty($birthDate)) return 0;

        try {
            $birth = new DateTime($birthDate);
            $now = new DateTime();
            return $now->diff($birth)->y;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Get user profile data for PDF generation
     * @param int $userId
     * @return array
     */
    private function getUserProfileData($userId) {
        try {
            $user = $this->userModel->findById($userId);

            return [
                // Complete user profile data - use request data as fallback
                'full_name' => $user['full_name'] ?? '',
                'nik' => $user['nik'] ?? '',
                'gender' => $user['gender'] ?? 'Laki-laki',
                'birth_place' => $user['birth_place'] ?? '-',
                'birth_date' => $user['birth_date'] ?? null,
                'religion' => $user['religion'] ?? 'Islam',
                'occupation' => $user['occupation'] ?? 'Wiraswasta',
                'nationality' => $user['nationality'] ?? 'WNI',
                'marital_status' => $user['marital_status'] ?? '',
                'phone' => $user['phone'] ?? '',
                'email' => $user['email'] ?? '',

                // Address information
                'address' => $user['address'] ?? '',
                'rt' => $user['rt'] ?? '',
                'rw' => $user['rw'] ?? '',
                'village' => $user['village'] ?? '',
                'district' => $user['district'] ?? '',
                'city' => $user['city'] ?? '',
                'province' => $user['province'] ?? '',
                'postal_code' => $user['postal_code'] ?? '',
            ];
        } catch (Exception $e) {
            error_log("Error getting user profile data: " . $e->getMessage());
            return [
                // Default fallback values
                'full_name' => '',
                'nik' => '',
                'gender' => 'Laki-laki',
                'birth_place' => '-',
                'birth_date' => null,
                'religion' => 'Islam',
                'occupation' => 'Wiraswasta',
                'nationality' => 'WNI',
                'address' => '',
            ];
        }
    }

    /**
     * Get base PDF data required for all templates
     * @param array $request
     * @param array $letterType
     * @return array
     */
    private function getBasePDFData($request, $letterType) {
        $tgl_lahir = $request['tanggal_lahir'] ?? $request['birth_date'] ?? '';
        if (!empty($tgl_lahir)) {
            $tgl_lahir = date('d-m-Y', strtotime($tgl_lahir));
        }

        return [
            'request' => $request,
            'letter_type' => $letterType,
            'current_date' => date('d F Y'),
            'letter_number' => $this->generateLetterNumber($request),
    
            // Desa Constants
            'kabupaten' => defined('DEFAULT_KABUPATEN') ? DEFAULT_KABUPATEN : 'Gianyar',
            'kecamatan' => defined('DEFAULT_KECAMATAN') ? DEFAULT_KECAMATAN : 'Blahbatuh',
            'desa' => defined('DEFAULT_DESA') ? DEFAULT_DESA : 'Penglipuran',
            'alamat_desa' => defined('DEFAULT_ALAMAT_DESA') ? DEFAULT_ALAMAT_DESA : '',
            'kepala_desa' => defined('DEFAULT_KEPALA_DESA') ? DEFAULT_KEPALA_DESA : '',
    
            // DATA PENDUDUK (Mapping Key Indo vs English DB)
            // Cek 'nama' (input form) -> 'nama_lengkap' -> 'full_name' (db users) -> 'user_full_name' (join)
            'nama' => $request['nama'] ?? $request['nama_lengkap'] ?? $request['full_name'] ?? $request['user_full_name'] ?? '',
            
            // Cek NIK
            'nik' => $request['nik'] ?? $request['user_nik'] ?? '',
            
            // Cek Jenis Kelamin (gender biasanya "Laki-laki"/"Perempuan" atau "M"/"F")
            'jenis_kelamin' => $request['jenis_kelamin'] ?? $request['gender'] ?? '',
            
            // Cek Tempat Lahir
            'tempat_lahir' => $request['tempat_lahir'] ?? $request['birth_place'] ?? '',
            
            // Cek Tanggal Lahir
            'tanggal_lahir' => $tgl_lahir,
            
            // Cek Agama
            'agama' => $request['agama'] ?? $request['religion'] ?? '',
            
            // Cek Pekerjaan
            'pekerjaan' => $request['pekerjaan'] ?? $request['occupation'] ?? '',
            
            // Cek Alamat
            'alamat' => $request['alamat'] ?? $request['address'] ?? '',
            
            // Warganegara
            'warganegara' => $request['warganegara'] ?? $request['nationality'] ?? (defined('DEFAULT_WARGANEGARA') ? DEFAULT_WARGANEGARA : 'WNI'),
    
            'nomor_surat' => $this->generateLetterNumber($request),
        ];
    }
    

    /**
     * Get template-specific data based on letter type
     * @param array $request
     * @return array
     */
    private function getTemplateSpecificData($request) {
        return [
            // Domisili data - use merged data from request (request_data + additional_data)
            'alamat_domisili' => $request['alamat_domisili'] ?? $request['address'] ?? '',

            // General purpose - use from merged request data
            'keperluan' => $request['keperluan'] ?? DEFAULT_KEPERLUAN,

            // Business certificate data - use merged data
            'nama_usaha' => $request['nama_usaha'] ?? BUSINESS_CERTIFICATE_PLACEHOLDER,
            'jenis_usaha' => $request['jenis_usaha'] ?? DEFAULT_JENIS_USAHA,
            'mulai_usaha' => $request['mulai_usaha'] ?? '........',
            'alamat_usaha' => $request['alamat_usaha'] ?? ($request['alamat'] ?? ''),
            'luas_usaha' => $request['luas_usaha'] ?? '',
            'tujuan' => $request['tujuan'] ?? BUSINESS_PURPOSE,
            'penghasilan' => $request['penghasilan'] ?? '',

            // Scholarship recommendation data - use merged data
            'nama_ayah' => $request['nama_ayah'] ?? '-',
            'sekolah' => $request['sekolah'] ?? SCHOOL_PLACEHOLDER,
            'nis_nim' => $request['nis_nim'] ?? '-',
            'semester' => $request['semester'] ?? '-',
            'jurusan' => $request['jurusan'] ?? '-',
            'nama_beasiswa' => $request['nama_beasiswa'] ?? DEFAULT_NAMA_BEASISWA,

            // Marriage data - use merged data
            'nik_pasangan' => $request['nik_pasangan'] ?? '',
            'nama_pasangan' => $request['nama_pasangan'] ?? '',

            // Event data - use merged data
            'nama_kegiatan' => $request['nama_kegiatan'] ?? DEFAULT_NAMA_KEGIATAN,
            'hari_kegiatan' => $request['hari_kegiatan'] ?? DEFAULT_HARI_KEGIATAN,
            'tanggal_kegiatan' => $request['tanggal_kegiatan'] ?? date('d-m-Y'),
            'waktu_kegiatan' => $request['waktu_kegiatan'] ?? DEFAULT_WAKTU_KEGIATAN,
            'tempat_kegiatan' => $request['tempat_kegiatan'] ?? ($request['address'] ?? ''),
            'hiburan' => $request['hiburan'] ?? DEFAULT_HIBURAN,

            // Calculate age if birth_date is available
            'umur' => !empty($request['birth_date']) ? $this->calculateAge($request['birth_date']) : ($request['umur'] ?? '0'),
        ];
    }

    /**
     * Generate PDF filename
     * @param array $letterType
     * @param array $request
     * @return string
     */
    private function generatePDFFilename($letterType, $request) {
        $safeName = preg_replace('/[^A-Za-z0-9\-_]/', '_', $request['user_full_name']);
        return "surat_{$letterType['code']}_{$request['id']}_{$safeName}.pdf";
    }

    /**
     * Get template filename from letter type name
     * @param string $letterTypeName
     * @return string
     */
    private function getTemplateName($letterTypeName) {
        // Map letter type names to actual template filenames
        $templateMap = [
            'Surat Keterangan' => 'surat_keterangan', // General letter
            'Surat Keterangan Domisili' => 'surat_keterangan_domisili',
            'Surat Keterangan Usaha' => 'surat_keterangan_usaha',
            'Surat Keterangan Tidak Mampu' => 'surat_keterangan_tidak_mampu',
            'Surat Pengantar Nikah' => 'surat_keterangan', // Use general template
            'Surat Izin Kegiatan' => 'surat_izin_kegiatan',
            'Surat Izin Usaha' => 'surat_izin_usaha',
            'Surat Keterangan Belum Menikah' => 'surat_keterangan_belum_menikah',
            'Surat Rekomendasi Beasiswa' => 'surat_rekomendasi_beasiswa',
        ];

        // Return mapped template or fallback to converted name
        return $templateMap[$letterTypeName] ?? strtolower(str_replace(' ', '_', $letterTypeName));
    }

    /**
     * Generate unique letter number
     * @param array $request
     * @return string
     */
    private function generateLetterNumber($request) {
        $year = date('Y');
        $month = date('n');
        $romanMonths = getRomanMonths();

        // Get sequential number for this month/year
        $sequential = $this->getNextLetterNumber($year, $month);

        return sprintf(LETTER_NUMBER_FORMAT, '510', $sequential, $romanMonths[$month], $year);
    }

    /**
     * Get next sequential letter number for given month/year
     * @param int $year
     * @param int $month
     * @return int
     */
    private function getNextLetterNumber($year, $month) {
        try {
            // Get the highest number for this month/year
            $result = fetchValue(
                "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(letter_number, '/', 2), '/', -1) AS UNSIGNED)), 0) as max_num
                 FROM letter_requests
                 WHERE letter_number LIKE ?
                 AND status = 'approved'",
                ["%/{$month}/{$year}"]
            );

            return $result + 1;

        } catch (Exception $e) {
            error_log("Error getting next letter number: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Generate Business Letter (SKU)
     */
    private function generateBusinessLetter($request) {
        return $this->generatePDFWithTemplate($request, 'surat_keterangan_usaha');
    }

    /**
     * Generate Marriage Letter (SKBM)
     */
    private function generateMarriageLetter($request) {
        return $this->generatePDFWithTemplate($request, 'surat_keterangan_belum_menikah');
    }

    /**
     * Generate Scholarship Recommendation Letter (SRB)
     */
    private function generateScholarshipLetter($request) {
        return $this->generatePDFWithTemplate($request, 'surat_rekomendasi_beasiswa');
    }

    /**
     * Generate Business Permit Letter (SIU)
     */
    private function generateBusinessPermitLetter($request) {
        return $this->generatePDFWithTemplate($request, 'surat_izin_usaha');
    }

    /**
     * Generate Event Permit Letter (SIK)
     */
    private function generateEventPermitLetter($request) {
        return $this->generatePDFWithTemplate($request, 'surat_izin_kegiatan');
    }

    /**
     * Generate Marriage Certificate Letter (SPN)
     */
    private function generateMarriageCertificateLetter($request) {
        return $this->generatePDFWithTemplate($request, 'surat_keterangan');
    }

    /**
     * Generate PDF using template file
     * @param array $request
     * @param string $templateName
     * @return string PDF content
     */
    private function generatePDFWithTemplate($request, $templateName) {

        $templatePath = TEMPLATE_DIR . $templateName . '.php';

        if (!file_exists($templatePath)) {
            error_log("Template not found: {$templatePath}");
            return $this->generateGenericLetter($request);
        }

        $data = array_merge(
            $request,
            $this->getTemplateSpecificData($request),
            [
                'userName' => $request['user_full_name'] ?? '',
                'nik' => $request['nik'] ?? 'N/A',
                'requestDate' => !empty($request['created_at']) 
                ? date('d F Y', strtotime($request['created_at'])) : '',
                'approvedDate' => date('d F Y'),

            ]
        );

        extract($data, EXTR_SKIP);

        // Load template and return content
        ob_start();
        include $templatePath;
        $html = ob_get_clean();

        return $html;
    }
}
