<?php
/**
 * Telegram Bot Utility Class
 *
 * Digunakan untuk mengirim notifikasi ke Telegram
 * ketika ada perubahan status permohonan surat
 */

class TelegramBot {
    private $botToken;
    private $apiUrl;
    private $debugMode;

    public function __construct() {
        // Check if config file exists
        $configFile = 'config/telegram.php';
        if (!file_exists($configFile)) {
            $this->log('File config telegram.php tidak ditemukan. Silakan copy dari telegram.php.example');
            throw new Exception('Konfigurasi Telegram belum diatur');
        }

        require_once $configFile;

        $this->botToken = defined('TELEGRAM_BOT_TOKEN') ? TELEGRAM_BOT_TOKEN : '';
        $this->apiUrl = defined('TELEGRAM_BOT_API_URL') ? TELEGRAM_BOT_API_URL : '';
        $this->debugMode = defined('TELEGRAM_DEBUG_MODE') ? TELEGRAM_DEBUG_MODE : false;

        // Pastikan bot token sudah diatur
        if (empty($this->botToken) || $this->botToken === 'YOUR_BOT_TOKEN_HERE') {
            $this->log('Bot token belum diatur. Silakan update TELEGRAM_BOT_TOKEN di config/telegram.php');
            throw new Exception('Bot token belum diatur');
        }
    }

    /**
     * Kirim pesan ke chat ID tertentu
     *
     * @param string $chatId Chat ID penerima
     * @param string $message Pesan yang akan dikirim
     * @param array $options Opsi tambahan (parse_mode, dll)
     * @return array Response dari Telegram API
     */
    public function sendMessage($chatId, $message, $options = []) {
        if (!TELEGRAM_NOTIFICATIONS_ENABLED) {
            $this->log('Notifikasi Telegram dinonaktifkan');
            return ['ok' => false, 'message' => 'Notifikasi dinonaktifkan'];
        }

        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ];

        // Gabungkan dengan opsi tambahan
        $data = array_merge($data, $options);

        $this->log("Mengirim pesan ke chat ID: $chatId");
        $this->log("Pesan: " . substr($message, 0, 100) . "...");

        $result = $this->makeRequest('sendMessage', $data);

        if ($result['ok']) {
            $this->log('Pesan berhasil dikirim');
        } else {
            $this->log('Gagal mengirim pesan: ' . ($result['description'] ?? 'Unknown error'));
        }

        return $result;
    }

    /**
     * Kirim dokumen/file ke chat ID tertentu
     *
     * @param string $chatId Chat ID penerima
     * @param string $filePath Path lengkap ke file yang akan dikirim
     * @param string $caption Caption untuk file (opsional)
     * @param array $options Opsi tambahan
     * @return array Response dari Telegram API
     */
    public function sendDocument($chatId, $filePath, $caption = '', $options = []) {
        if (!TELEGRAM_NOTIFICATIONS_ENABLED) {
            $this->log('Notifikasi Telegram dinonaktifkan');
            return ['ok' => false, 'message' => 'Notifikasi dinonaktifkan'];
        }

        if (!file_exists($filePath)) {
            $this->log("File tidak ditemukan: $filePath");
            return ['ok' => false, 'message' => 'File tidak ditemukan'];
        }

        if (!is_readable($filePath)) {
            $this->log("File tidak dapat dibaca: $filePath");
            return ['ok' => false, 'message' => 'File tidak dapat dibaca'];
        }

        $fileName = basename($filePath);
        $fileSize = filesize($filePath);

        $this->log("Mengirim dokumen ke chat ID: $chatId");
        $this->log("File: $fileName ($fileSize bytes)");

        // Siapkan data untuk CURL
        $postData = [
            'chat_id' => $chatId,
            'document' => new CURLFile($filePath, $this->getMimeType($filePath), $fileName),
            'caption' => $caption,
            'parse_mode' => 'Markdown'
        ];

        // Gabungkan dengan opsi tambahan
        $postData = array_merge($postData, $options);

        $result = $this->makeMultipartRequest('sendDocument', $postData);

        if ($result['ok']) {
            $this->log('Dokumen berhasil dikirim');
        } else {
            $this->log('Gagal mengirim dokumen: ' . ($result['description'] ?? 'Unknown error'));
        }

        return $result;
    }

    /**
     * Kirim notifikasi persetujuan surat dengan file PDF
     *
     * @param array $requestData Data permohonan surat
     * @param string $adminName Nama admin yang menyetujui
     * @param string $pdfFilePath Path lengkap ke file PDF (opsional)
     * @return array Response dari Telegram API
     */
    public function sendApprovalNotification($requestData, $adminName = 'Admin', $pdfFilePath = null) {
        $chatId = $this->getUserChatId($requestData['user_id']);

        if (!$chatId) {
            $this->log('Chat ID tidak ditemukan untuk user ID: ' . $requestData['user_id']);
            return ['ok' => false, 'message' => 'Chat ID tidak ditemukan'];
        }

        $message = $this->formatApprovalMessage($requestData, $adminName);

        // Kirim pesan notifikasi terlebih dahulu
        $messageResult = $this->sendMessage($chatId, $message);

        // Jika ada file PDF, kirim juga file PDF
        if ($pdfFilePath && file_exists($pdfFilePath)) {
            $this->log("Mengirim file PDF: $pdfFilePath");
            $documentResult = $this->sendDocument($chatId, $pdfFilePath, "📄 Surat {$requestData['letter_type_name']} - ID: {$requestData['id']}");

            return [
                'ok' => $messageResult['ok'] && $documentResult['ok'],
                'message_result' => $messageResult,
                'document_result' => $documentResult
            ];
        }

        return $messageResult;
    }

    /**
     * Kirim notifikasi penolakan surat
     *
     * @param array $requestData Data permohonan surat
     * @param string $adminName Nama admin yang menolak
     * @param string $notes Catatan penolakan
     * @return array Response dari Telegram API
     */
    public function sendRejectionNotification($requestData, $adminName = 'Admin', $notes = '') {
        $chatId = $this->getUserChatId($requestData['user_id']);

        if (!$chatId) {
            $this->log('Chat ID tidak ditemukan untuk user ID: ' . $requestData['user_id']);
            return ['ok' => false, 'message' => 'Chat ID tidak ditemukan'];
        }

        $message = $this->formatRejectionMessage($requestData, $adminName, $notes);

        return $this->sendMessage($chatId, $message);
    }

    /**
     * Format pesan persetujuan
     */
    private function formatApprovalMessage($requestData, $adminName) {
        $message = TELEGRAM_MESSAGE_APPROVED;

        $replacements = [
            '%REQUEST_ID%' => $requestData['id'],
            '%LETTER_TYPE%' => $requestData['letter_type_name'] ?? 'Tidak diketahui',
            '%REQUEST_DATE%' => date(TELEGRAM_DATE_FORMAT, strtotime($requestData['created_at'])),
            '%USER_NAME%' => $requestData['user_full_name'] ?? 'Tidak diketahui',
            '%USER_EMAIL%' => $requestData['user_email'] ?? 'Tidak diketahui',
            '%APPROVED_DATE%' => date(TELEGRAM_DATE_FORMAT),
            '%ADMIN_NAME%' => $adminName
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }

    /**
     * Format pesan penolakan
     */
    private function formatRejectionMessage($requestData, $adminName, $notes) {
        $message = TELEGRAM_MESSAGE_REJECTED;

        $replacements = [
            '%REQUEST_ID%' => $requestData['id'],
            '%LETTER_TYPE%' => $requestData['letter_type_name'] ?? 'Tidak diketahui',
            '%REQUEST_DATE%' => date(TELEGRAM_DATE_FORMAT, strtotime($requestData['created_at'])),
            '%USER_NAME%' => $requestData['user_full_name'] ?? 'Tidak diketahui',
            '%USER_EMAIL%' => $requestData['user_email'] ?? 'Tidak diketahui',
            '%REJECTED_DATE%' => date(TELEGRAM_DATE_FORMAT),
            '%ADMIN_NAME%' => $adminName,
            '%NOTES%' => $notes ?: 'Tidak ada catatan'
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }

    /**
     * Dapatkan Chat ID pengguna dari database
     * Note: Ini memerlukan kolom telegram_chat_id di tabel users
     *
     * @param int $userId ID pengguna
     * @return string|null Chat ID atau null jika tidak ditemukan
     */
    private function getUserChatId($userId) {
        try {
            require_once 'app/models/User.php';
            $userModel = new User();

            // Get user by ID first, then return telegram_chat_id
            $user = $userModel->findById($userId);

            return $user['telegram_chat_id'] ?? null;
        } catch (Exception $e) {
            $this->log('Error getting user chat ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Set Chat ID untuk pengguna
     * Digunakan ketika user memulai percakapan dengan bot
     *
     * @param int $userId ID pengguna
     * @param string $chatId Chat ID dari Telegram
     * @return bool Berhasil atau tidak
     */
    public function setUserChatId($userId, $chatId) {
        try {
            require_once 'app/models/User.php';
            $userModel = new User();

            // Update telegram_chat_id untuk user
            return $userModel->update($userId, ['telegram_chat_id' => $chatId]);
        } catch (Exception $e) {
            $this->log('Error setting user chat ID: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test koneksi bot
     *
     * @return array Response dari Telegram API
     */
    public function testConnection() {
        $this->log('Testing bot connection...');
        return $this->makeRequest('getMe');
    }

    /**
     * Kirim request ke Telegram API
     *
     * @param string $method Method API
     * @param array $data Data yang akan dikirim
     * @return array Response dari API
     */
    private function makeRequest($method, $data = []) {
        $url = $this->apiUrl . $method;

        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($data),
                'timeout' => 30
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            $error = error_get_last();
            $this->log('HTTP request failed: ' . ($error['message'] ?? 'Unknown error'));
            return ['ok' => false, 'error' => $error['message'] ?? 'HTTP request failed'];
        }

        $response = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->log('JSON decode error: ' . json_last_error_msg());
            return ['ok' => false, 'error' => 'JSON decode error'];
        }

        return $response;
    }

    /**
     * Kirim multipart request ke Telegram API
     *
     * @param string $method Method API
     * @param array $postData Data POST multipart
     * @return array Response dari API
     */
    private function makeMultipartRequest($method, $postData) {
        $url = $this->apiUrl . $method;

        // Jika CURL tersedia, gunakan CURL
        if (function_exists('curl_init')) {
            return $this->makeCurlRequest($url, $postData);
        }

        // Fallback menggunakan file_get_contents (kurang reliable untuk file upload)
        $this->log('CURL not available, using file_get_contents fallback');

        $boundary = '----TelegramBotBoundary' . uniqid();
        $body = $this->buildMultipartBodyFromArray($postData, $boundary);

        $options = [
            'http' => [
                'header' => "Content-Type: multipart/form-data; boundary=$boundary\r\n",
                'method' => 'POST',
                'content' => $body,
                'timeout' => 60
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            $error = error_get_last();
            $this->log('Multipart HTTP request failed: ' . ($error['message'] ?? 'Unknown error'));
            return ['ok' => false, 'error' => $error['message'] ?? 'HTTP request failed'];
        }

        $response = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->log('JSON decode error: ' . json_last_error_msg());
            return ['ok' => false, 'error' => 'JSON decode error'];
        }

        return $response;
    }

    /**
     * Kirim request menggunakan CURL (lebih reliable)
     *
     * @param string $url URL API
     * @param array $postData Data POST
     * @return array Response dari API
     */
    private function makeCurlRequest($url, $postData) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // Set header untuk multipart/form-data
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data'
        ]);

        $result = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($result === false) {
            $this->log('CURL request failed: ' . $error);
            return ['ok' => false, 'error' => $error];
        }

        $this->log("HTTP Response Code: $httpCode");

        $response = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->log('JSON decode error: ' . json_last_error_msg());
            $this->log('Raw response: ' . substr($result, 0, 200));
            return ['ok' => false, 'error' => 'JSON decode error'];
        }

        return $response;
    }

    /**
     * Build multipart form data body from array (fallback method)
     *
     * @param array $data Data form
     * @param string $boundary Boundary string
     * @return string Multipart body
     */
    private function buildMultipartBodyFromArray($data, $boundary) {
        $body = '';

        foreach ($data as $name => $value) {
            $body .= "--$boundary\r\n";

            if ($value instanceof CURLFile) {
                // File upload
                $body .= "Content-Disposition: form-data; name=\"$name\"; filename=\"{$value->getPostFilename()}\"\r\n";
                $body .= "Content-Type: {$value->getMimeType()}\r\n\r\n";
                $body .= file_get_contents($value->getFilename()) . "\r\n";
            } else {
                // Regular field
                $body .= "Content-Disposition: form-data; name=\"$name\"\r\n\r\n";
                $body .= $value . "\r\n";
            }
        }

        $body .= "--$boundary--\r\n";
        return $body;
    }

    /**
     * Get MIME type for file
     *
     * @param string $filePath Path to file
     * @return string MIME type
     */
    private function getMimeType($filePath) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain'
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Log aktivitas bot
     *
     * @param string $message Pesan log
     */
    private function log($message) {
        if (!$this->debugMode) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";

        $logFile = TELEGRAM_LOG_FILE;

        // Pastikan direktori log ada
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Set webhook untuk bot (opsional)
     * Digunakan jika ingin bot menerima pesan dari user
     *
     * @param string $webhookUrl URL webhook
     * @return array Response dari API
     */
    public function setWebhook($webhookUrl) {
        $this->log("Setting webhook to: $webhookUrl");
        return $this->makeRequest('setWebhook', ['url' => $webhookUrl]);
    }

    /**
     * Hapus webhook
     *
     * @return array Response dari API
     */
    public function deleteWebhook() {
        $this->log('Deleting webhook');
        return $this->makeRequest('deleteWebhook');
    }
}
?>
