<?php
/**
 * Admin Controller
 * ABIS - Aplikasi Desa Digital
 */

require_once 'config/letter_constants.php';

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

        // Prepare data for view
        $data = [
            'stats' => $stats,
            'recent_requests' => $recent_requests,
            'recent_activities' => $recent_activities,
            'pending_requests' => $pending_requests,
            'recent_users' => $recent_users
        ];

        // Extract data to make variables available in view
        extract($data);

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
     * Display letter requests management (approve/reject)
     */
    public function letterRequests() {
        requireAuth('admin');

        $page = $_GET['page'] ?? 1;
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';

        $filters = [];
        if (!empty($status)) $filters['status'] = $status;
        if (!empty($search)) $filters['search'] = $search;

        $requests_data = $this->letterRequestModel->getAll($page, ITEMS_PER_PAGE, $filters);

        // Prepare data for view
        $data = [
            'title' => 'Pengajuan Surat',
            'extra_css' => ['admin-dashboard.css'],
            'extra_js' => ['admin-dashboard.js'],
            'requests_data' => $requests_data,
            'status' => $status,
            'search' => $search
        ];

        // Extract data to make variables available in view
        extract($data);

        // Load view
        ob_start();
        include VIEWS_DIR . '/admin/letter-requests.php';
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
     * Display letter types management
     */
    public function letterTypes() {
        requireAuth('admin');

        $letterTypes = $this->letterTypeModel->getAll();

        // Load view
        ob_start();
        include VIEWS_DIR . '/admin/letter-types.php';
        $content = ob_get_clean();

        // Load admin layout
        include VIEWS_DIR . '/layouts/admin.php';
    }

    /**
     * Create new letter type
     */
    public function createLetterType() {
        requireAuth('admin');

        $data = [
            'name' => $_POST['name'] ?? '',
            'code' => $_POST['code'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if (empty($data['name']) || empty($data['code'])) {
            $_SESSION['error'] = 'Nama dan kode jenis surat harus diisi.';
            header('Location: ' . BASE_URL . '/admin/letter-types');
            exit;
        }

        $result = $this->letterTypeModel->create($data);

        if ($result) {
            $_SESSION['success'] = 'Jenis surat berhasil ditambahkan.';
        } else {
            $_SESSION['error'] = 'Gagal menambahkan jenis surat.';
        }

        header('Location: ' . BASE_URL . '/admin/letter-types');
        exit;
    }

    /**
     * Update letter type
     */
    public function updateLetterType($id) {
        requireAuth('admin');

        $data = [
            'name' => $_POST['name'] ?? '',
            'code' => $_POST['code'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        if (empty($data['name']) || empty($data['code'])) {
            $_SESSION['error'] = 'Nama dan kode jenis surat harus diisi.';
            header('Location: ' . BASE_URL . '/admin/letter-types');
            exit;
        }

        $result = $this->letterTypeModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Jenis surat berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui jenis surat.';
        }

        header('Location: ' . BASE_URL . '/admin/letter-types');
        exit;
    }

    /**
     * Toggle letter type status
     */
    public function toggleLetterTypeStatus($id) {
        requireAuth('admin');

        $type = $this->letterTypeModel->findById($id);
        if (!$type) {
            $_SESSION['error'] = 'Jenis surat tidak ditemukan.';
            header('Location: ' . BASE_URL . '/admin/letter-types');
            exit;
        }

        $newStatus = $type['is_active'] ? 0 : 1;
        $result = $this->letterTypeModel->update($id, ['is_active' => $newStatus]);

        if ($result) {
            $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            $_SESSION['success'] = "Jenis surat berhasil {$statusText}.";
        } else {
            $_SESSION['error'] = 'Gagal mengubah status jenis surat.';
        }

        header('Location: ' . BASE_URL . '/admin/letter-types');
        exit;
    }

    /**
     * Export requests to Excel
     */
    public function exportExcel() {
        requireAuth('admin');

        // Get filter parameters
        $dari_tanggal = $_GET['dari_tanggal'] ?? '';
        $sampai_tanggal = $_GET['sampai_tanggal'] ?? '';
        $jenis_keterangan = $_GET['jenis_keterangan'] ?? '';
        $jenis_pengantar = $_GET['jenis_pengantar'] ?? '';
        $jenis_lainnya = $_GET['jenis_lainnya'] ?? '';

        // Build filters
        $filters = [];
        if (!empty($dari_tanggal)) $filters['dari_tanggal'] = $dari_tanggal;
        if (!empty($sampai_tanggal)) $filters['sampai_tanggal'] = $sampai_tanggal;
        if (!empty($jenis_keterangan)) $filters['jenis_keterangan'] = true;
        if (!empty($jenis_pengantar)) $filters['jenis_pengantar'] = true;
        if (!empty($jenis_lainnya)) $filters['jenis_lainnya'] = true;

        // Get all requests without pagination
        $requests = $this->letterRequestModel->getAllForExport($filters);

        // Create Excel file
        $this->createExcelFile($requests);
    }

    /**
     * Generate and download PDF for approved request
     */
    public function downloadRequest($requestId) {
        requireAuth('admin');

        $request = $this->letterRequestModel->findById($requestId);

        if (!$request) {
            $_SESSION['error'] = 'Pengajuan surat tidak ditemukan.';
            header('Location: ' . BASE_URL . '/admin/letter-requests');
            exit;
        }

        if ($request['status'] !== 'approved') {
            $_SESSION['error'] = 'Surat hanya dapat didownload jika sudah disetujui.';
            header('Location: ' . BASE_URL . '/admin/letter-requests');
            exit;
        }

        // Check if PDF already generated
        if (empty($request['generated_file'])) {
            // Generate PDF if not exists
            $this->generateLetterPDF($requestId);
            // Re-fetch request data
            $request = $this->letterRequestModel->findById($requestId);
        }

        if (empty($request['generated_file'])) {
            $_SESSION['error'] = 'Gagal membuat file PDF.';
            header('Location: ' . BASE_URL . '/admin/letter-requests');
            exit;
        }

        // Download the file
        require_once 'utils/PDFGenerator.php';
        $pdfGenerator = new PDFGenerator();
        $filePath = $pdfGenerator->getFilePath($request['generated_file']);

        if (!$pdfGenerator->fileExists($request['generated_file'])) {
            $_SESSION['error'] = 'File PDF tidak ditemukan.';
            header('Location: ' . BASE_URL . '/admin/letter-requests');
            exit;
        }

        // Force download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
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
            case 'SK-KTP':
                return $this->generateKTPLetter($request);
            case 'SK-DOM':
                return $this->generateDomicileLetter($request);
            case 'SK-TM':
                return $this->generatePoorLetter($request);
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
     * Create and download Excel file
     */
    private function createExcelFile($requests) {
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="data_surat_' . date('Y-m-d_H-i-s') . '.xls"');
        header('Cache-Control: max-age=0');

        // Create Excel content
        $excelContent = $this->generateExcelContent($requests);

        echo $excelContent;
        exit;
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
        }

        // If called via AJAX, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $success ? 'Permohonan berhasil disetujui' : 'Gagal menyetujui permohonan']);
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
        $baseData = $this->getBasePDFData($request, $letterType);
        $templateSpecificData = $this->getTemplateSpecificData($request);

        return array_merge($baseData, $templateSpecificData);
    }

    /**
     * Get base PDF data required for all templates
     * @param array $request
     * @param array $letterType
     * @return array
     */
    private function getBasePDFData($request, $letterType) {
        return [
            'request' => $request,
            'letter_type' => $letterType,
            'current_date' => date('d F Y'),
            'letter_number' => $this->generateLetterNumber($request),

            // Village/Regional data
            'kabupaten' => DEFAULT_KABUPATEN,
            'kecamatan' => DEFAULT_KECAMATAN,
            'desa' => DEFAULT_DESA,
            'alamat_desa' => DEFAULT_ALAMAT_DESA,
            'kepala_desa' => DEFAULT_KEPALA_DESA,

            // Personal data
            'nama' => $request['user_full_name'] ?? '',
            'nik' => $request['nik'] ?? '',
            'jenis_kelamin' => $request['gender'] ?? '',
            'tempat_lahir' => $request['birth_place'] ?? '',
            'tanggal_lahir' => $request['birth_date'] ?? '',
            'warganegara' => DEFAULT_WARGANEGARA,
            'agama' => $request['religion'] ?? '',
            'pekerjaan' => $request['occupation'] ?? DEFAULT_PEKERJAAN,
            'alamat' => $request['address'] ?? '',
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
            // Domisili data
            'alamat_domisili' => $request['address'] ?? '',

            // General purpose
            'keperluan' => DEFAULT_KEPERLUAN,

            // Business certificate data
            'nama_usaha' => BUSINESS_CERTIFICATE_PLACEHOLDER,
            'mulai_usaha' => BUSINESS_START_YEAR,
            'alamat_usaha' => $request['address'] ?? '',
            'tujuan' => BUSINESS_PURPOSE,

            // Scholarship recommendation data
            'nama_ayah' => '-',
            'sekolah' => SCHOOL_PLACEHOLDER,
            'nis_nim' => '-',
            'semester' => '-',
            'jurusan' => '-',
            'nama_beasiswa' => DEFAULT_NAMA_BEASISWA,

            // Business permit data
            'jenis_usaha' => DEFAULT_JENIS_USAHA,
            'luas_usaha' => '',

            // Event permit data
            'umur' => '...',
            'nama_kegiatan' => DEFAULT_NAMA_KEGIATAN,
            'hari_kegiatan' => DEFAULT_HARI_KEGIATAN,
            'tanggal_kegiatan' => date('d-m-Y'),
            'waktu_kegiatan' => DEFAULT_WAKTU_KEGIATAN,
            'tempat_kegiatan' => $request['address'] ?? '',
            'hiburan' => DEFAULT_HIBURAN,
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
        return strtolower(str_replace(' ', '_', $letterTypeName));
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
}
