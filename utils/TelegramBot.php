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
        require_once 'config/telegram.php';

        $this->botToken = TELEGRAM_BOT_TOKEN;
        $this->apiUrl = TELEGRAM_BOT_API_URL;
        $this->debugMode = TELEGRAM_DEBUG_MODE;

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
     * Kirim notifikasi persetujuan surat
     *
     * @param array $requestData Data permohonan surat
     * @param string $adminName Nama admin yang menyetujui
     * @return array Response dari Telegram API
     */
    public function sendApprovalNotification($requestData, $adminName = 'Admin') {
        $chatId = $this->getUserChatId($requestData['user_id']);

        if (!$chatId) {
            $this->log('Chat ID tidak ditemukan untuk user ID: ' . $requestData['user_id']);
            return ['ok' => false, 'message' => 'Chat ID tidak ditemukan'];
        }

        $message = $this->formatApprovalMessage($requestData, $adminName);

        return $this->sendMessage($chatId, $message);
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
