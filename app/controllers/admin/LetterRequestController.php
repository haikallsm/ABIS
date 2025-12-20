<?php

/**
 * Admin Letter Request Controller
 * Handles letter request management, approval, and rejection
 *
 * ABIS - Aplikasi Desa Digital
 */

require_once 'config/session.php';
require_once 'app/models/LetterRequest.php';
require_once 'app/models/LetterType.php';
require_once 'app/models/User.php';
require_once 'app/services/LetterService.php';
require_once 'utils/TelegramBot.php';

class LetterRequestController
{
    private $letterRequestModel;
    private $letterTypeModel;
    private $userModel;
    private $letterService;

    public function __construct()
    {
        $this->letterRequestModel = new LetterRequest();
        $this->letterTypeModel = new LetterType();
        $this->userModel = new User();
        $this->letterService = new LetterService();
    }

    /**
     * Display letter requests list
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? null;

        // Get requests with pagination and filters
        $requests = $this->letterRequestModel->getAll($limit, $offset, $status, $search);
        $totalRequests = $this->letterRequestModel->countAll($status, $search);
        $totalPages = ceil($totalRequests / $limit);

        // Get letter types for filter dropdown
        $letterTypes = $this->letterTypeModel->getAllActive();

        $viewData = [
            'requests' => $requests,
            'letterTypes' => $letterTypes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalRequests' => $totalRequests,
            'statusFilter' => $status,
            'searchQuery' => $search,
            'pageTitle' => 'Pengajuan Surat'
        ];

        $this->render('admin/letter-requests', $viewData);
    }

    /**
     * Display letter request details (PDF preview)
     */
    public function show($requestId)
    {
        try {
            $request = $this->letterRequestModel->findById($requestId);
            if (!$request) {
                throw new Exception('Pengajuan surat tidak ditemukan');
            }

            if ($request['status'] !== STATUS_APPROVED) {
                throw new Exception('Surat belum disetujui - tidak dapat ditampilkan');
            }

            if (empty($request['generated_file'])) {
                throw new Exception('File surat belum dibuat');
            }

            // Get PDF file path using service
            $filePath = $this->letterService->getLetterFilePath($requestId);

            if (!$filePath || !file_exists($filePath)) {
                throw new Exception('File surat tidak ditemukan');
            }

            // Set headers for inline PDF display (preview)
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            header('Accept-Ranges: bytes');

            // Clear output buffer
            if (ob_get_level()) {
                ob_clean();
            }
            flush();

            // Output file for inline display
            readfile($filePath);
            exit;

        } catch (Exception $e) {
            // Show error page instead of PDF
            $errorMessage = $e->getMessage();
            echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Preview Surat - Error</title>
    <link href='" . ASSETS_URL . "/css/admin-dashboard.css' rel='stylesheet'>
</head>
<body class='cream-bg'>
    <div class='min-h-screen flex items-center justify-center'>
        <div class='max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center'>
            <div class='mb-6'>
                <svg class='mx-auto h-16 w-16 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z'></path>
                </svg>
            </div>
            <h2 class='text-xl font-bold text-gray-800 mb-4'>Preview Tidak Tersedia</h2>
            <p class='text-gray-600 mb-6'>{$errorMessage}</p>
            <a href='/admin/letter-requests' class='bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-light transition-colors'>
                Kembali ke Daftar Pengajuan
            </a>
        </div>
    </div>
</body>
</html>";
            exit;
        }
    }

    /**
     * Approve letter request via AJAX
     */
    public function approve($requestId)
    {
        try {
            // Validate request
            $request = $this->letterRequestModel->findById($requestId);
            if (!$request) {
                throw new Exception('Pengajuan surat tidak ditemukan');
            }

            if ($request['status'] !== STATUS_PENDING) {
                throw new Exception('Pengajuan surat sudah diproses');
            }

            // Get admin ID
            $adminId = getCurrentUserId();
            $notes = $_POST['notes'] ?? '';

            // Process approval using service
            $success = $this->letterService->approveLetter($requestId, $adminId, $notes);

            if (!$success) {
                throw new Exception('Gagal menyetujui pengajuan surat');
            }

            // Send Telegram notification if enabled
            $this->sendApprovalNotification($requestId);

            $this->jsonResponse([
                'success' => true,
                'message' => 'Pengajuan surat berhasil disetujui',
                'data' => [
                    'request_id' => $requestId,
                    'status' => STATUS_APPROVED
                ]
            ]);

        } catch (Exception $e) {
            error_log('Letter approval failed: ' . $e->getMessage());
            $this->jsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject letter request via AJAX
     */
    public function reject($requestId)
    {
        try {
            // Validate request
            $request = $this->letterRequestModel->findById($requestId);
            if (!$request) {
                throw new Exception('Pengajuan surat tidak ditemukan');
            }

            if ($request['status'] !== STATUS_PENDING) {
                throw new Exception('Pengajuan surat sudah diproses');
            }

            // Get admin ID
            $adminId = getCurrentUserId();
            $notes = $_POST['notes'] ?? '';

            // Process rejection using service
            $success = $this->letterService->rejectLetter($requestId, $adminId, $notes);

            if (!$success) {
                throw new Exception('Gagal menolak pengajuan surat');
            }

            // Send Telegram notification if enabled
            $this->sendRejectionNotification($requestId);

            $this->jsonResponse([
                'success' => true,
                'message' => 'Pengajuan surat berhasil ditolak',
                'data' => [
                    'request_id' => $requestId,
                    'status' => STATUS_REJECTED
                ]
            ]);

        } catch (Exception $e) {
            error_log('Letter rejection failed: ' . $e->getMessage());
            $this->jsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download generated letter PDF
     */
    public function download($requestId)
    {
        try {
            $request = $this->letterRequestModel->findById($requestId);
            if (!$request) {
                throw new Exception('Pengajuan surat tidak ditemukan');
            }

            if ($request['status'] !== STATUS_APPROVED) {
                throw new Exception('Surat belum disetujui');
            }

            // Get file path using service
            $filePath = $this->letterService->getLetterFilePath($requestId);

            if (!$filePath || !file_exists($filePath)) {
                throw new Exception('File surat tidak ditemukan');
            }

            // Set headers for download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            // Clear output buffer and flush
            if (ob_get_level()) {
                ob_clean();
            }
            flush();

            // Output file
            readfile($filePath);
            exit;

        } catch (Exception $e) {
            $this->redirect('/admin/letter-requests', $e->getMessage());
        }
    }

    /**
     * Delete letter request
     */
    public function delete($requestId)
    {
        try {
            $request = $this->letterRequestModel->findById($requestId);
            if (!$request) {
                throw new Exception('Pengajuan surat tidak ditemukan');
            }

            // Only allow deletion of pending requests
            if ($request['status'] !== STATUS_PENDING) {
                throw new Exception('Hanya pengajuan yang belum diproses yang dapat dihapus');
            }

            $success = $this->letterRequestModel->delete($requestId);

            if (!$success) {
                throw new Exception('Gagal menghapus pengajuan surat');
            }

            $this->redirect('/admin/letter-requests', 'Pengajuan surat berhasil dihapus', 'success');

        } catch (Exception $e) {
            $this->redirect('/admin/letter-requests', $e->getMessage(), 'error');
        }
    }

    /**
     * Send approval notification via Telegram
     */
    private function sendApprovalNotification($requestId)
    {
        try {
            $telegramBot = new TelegramBot();

            // Get fresh request data to ensure we have the latest generated file
            $request = $this->letterRequestModel->findById($requestId);

            if ($request && TELEGRAM_NOTIFICATIONS_ENABLED) {
                $user = $this->userModel->findById($request['user_id']);
                if ($user && !empty($user['telegram_chat_id'])) {
                    // Get admin name from current session
                    $adminData = getCurrentUser();
                    $adminName = $adminData ? $adminData['full_name'] : 'Admin';

                    // Get PDF file path using LetterService
                    $pdfFilePath = $this->letterService->getLetterFilePath($requestId);

                    // Wait for file to be available (with retry logic)
                    $maxRetries = 5;
                    $retryCount = 0;
                    $fileReady = false;

                    while ($retryCount < $maxRetries && !$fileReady) {
                        if ($pdfFilePath && file_exists($pdfFilePath) && filesize($pdfFilePath) > 0) {
                            $fileReady = true;
                            error_log('PDF file found and ready to send: ' . $pdfFilePath . ' (size: ' . filesize($pdfFilePath) . ' bytes)');
                        } else {
                            $retryCount++;
                            if ($retryCount < $maxRetries) {
                                error_log('PDF file not ready yet, retrying in 1 second... (attempt ' . $retryCount . '/' . $maxRetries . ')');
                                sleep(1); // Wait 1 second before retry
                                // Refresh the path in case it was updated
                                $pdfFilePath = $this->letterService->getLetterFilePath($requestId);
                            }
                        }
                    }

                    if (!$fileReady) {
                        error_log('PDF file not found after ' . $maxRetries . ' retries: ' . ($pdfFilePath ?? 'null'));
                        $pdfFilePath = null;
                    }

                    // Send notification with PDF attachment if available
                    $telegramBot->sendApprovalNotification(
                        $request,  // Pass full request data array
                        $adminName, // Admin name
                        $pdfFilePath // PDF file path (null if not available)
                    );
                }
            }
        } catch (Exception $e) {
            error_log('Telegram approval notification failed: ' . $e->getMessage());
            // Don't fail the main process if notification fails
        }
    }

    /**
     * Send rejection notification via Telegram
     */
    private function sendRejectionNotification($requestId)
    {
        try {
            $telegramBot = new TelegramBot();
            $request = $this->letterRequestModel->findById($requestId);

            if ($request && TELEGRAM_NOTIFICATIONS_ENABLED) {
                $user = $this->userModel->findById($request['user_id']);
                if ($user && !empty($user['telegram_chat_id'])) {
                    $telegramBot->sendRejectionNotification(
                        $user['telegram_chat_id'],
                        $request['letter_type_name']
                    );
                }
            }
        } catch (Exception $e) {
            error_log('Telegram rejection notification failed: ' . $e->getMessage());
            // Don't fail the main process if notification fails
        }
    }

    /**
     * Render view helper
     */
    private function render($view, $data = [])
    {
        extract($data);
        ob_start();
        $viewPath = VIEWS_DIR . '/' . $view . '.php';
        if (!file_exists($viewPath)) {
            die("View file not found: {$viewPath}");
        }
        include $viewPath;
        $content = ob_get_clean();
        include VIEWS_DIR . '/layouts/admin.php';
    }

    /**
     * Redirect helper
     */
    private function redirect($url, $message = '', $type = 'info')
    {
        if (!empty($message)) {
            setFlashMessage($message, $type);
        }
        header("Location: " . BASE_URL . $url);
        exit;
    }

    /**
     * JSON response helper
     */
    private function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
