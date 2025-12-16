<?php

/**
 * Telegram Bot Webhook Handler
 *
 * Menangani pesan yang diterima dari Telegram bot
 * untuk menyimpan Chat ID user secara otomatis
 */

require_once 'config/config.php';
require_once 'utils/TelegramBot.php';
require_once 'app/models/User.php';
initApp();

// Pastikan ini adalah POST request dari Telegram
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

// Ambil data dari webhook
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['message'])) {
    http_response_code(400);
    die('Invalid webhook data');
}

$message = $data['message'];
$chatId = $message['chat']['id'];
$text = trim($message['text'] ?? '');
$userTelegramId = $message['from']['id'] ?? null;

// Log webhook activity
error_log("Telegram Webhook: Chat ID {$chatId}, Message: {$text}");

try {
    $userModel = new User();

    // Cari user berdasarkan telegram_chat_id atau username
    $user = null;

    // Jika user sudah memiliki chat_id, gunakan itu
    if ($userTelegramId) {
        $user = $userModel->findByTelegramId($userTelegramId);
    }

    // Jika belum ada, coba cari berdasarkan username dari text
    if (!$user && strpos($text, '@') === 0) {
        $username = substr($text, 1); // Remove @ symbol
        $user = $userModel->findByUsername($username);
    }

    if ($user) {
        // Update Chat ID untuk user
        $userModel->update($user['id'], ['telegram_chat_id' => $chatId]);

        // Kirim konfirmasi
        $telegramBot = new TelegramBot();
        $telegramBot->sendMessage($chatId,
            "✅ *Akun Telegram Berhasil Terhubung!*\n\n" .
            "Hai {$user['full_name']}!\n\n" .
            "Akun Telegram Anda sekarang terhubung dengan sistem Surat-In.\n" .
            "Anda akan menerima notifikasi ketika status permohonan surat Anda berubah.\n\n" .
            "📋 *Cara menggunakan:*\n" .
            "1. Buat permohonan surat di website\n" .
            "2. Admin akan memproses permohonan\n" .
            "3. Anda akan mendapat notifikasi di sini\n\n" .
            "Terima kasih telah menggunakan layanan kami! 🚀"
        );

        error_log("Telegram Webhook: Successfully linked Chat ID {$chatId} to user {$user['username']}");
    } else {
        // User tidak ditemukan, kirim pesan panduan
        $telegramBot = new TelegramBot();
        $telegramBot->sendMessage($chatId,
            "🤖 *Bot Surat-In*\n\n" .
            "Hai! Terima kasih telah menghubungi bot ini.\n\n" .
            "Untuk menghubungkan akun Telegram Anda dengan sistem Surat-In:\n\n" .
            "1. Login ke website Surat-In\n" .
            "2. Pergi ke Dashboard\n" .
            "3. Cari bagian 'Notifikasi Telegram'\n" .
            "4. Masukkan Chat ID Anda: `{$chatId}`\n" .
            "5. Klik 'Hubungkan'\n\n" .
            "Atau kirim username Anda (contoh: @username) untuk menghubungkan otomatis.\n\n" .
            "Butuh bantuan? Hubungi admin sistem. 👨‍💼"
        );

        // Simpan Chat ID sementara untuk referensi
        error_log("Telegram Webhook: Unknown user with Chat ID {$chatId}, sent instructions");
    }

} catch (Exception $e) {
    error_log("Telegram Webhook Error: " . $e->getMessage());

    // Kirim pesan error ke user
    try {
        $telegramBot = new TelegramBot();
        $telegramBot->sendMessage($chatId,
            "❌ *Terjadi Kesalahan*\n\n" .
            "Maaf, terjadi kesalahan saat memproses permintaan Anda.\n" .
            "Silakan coba lagi atau hubungi admin sistem.\n\n" .
            "Error: " . $e->getMessage()
        );
    } catch (Exception $e2) {
        error_log("Failed to send error message: " . $e2->getMessage());
    }
}

// Telegram expects 200 OK response
http_response_code(200);
echo 'OK';

?>
