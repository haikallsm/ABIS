<?php
/**
 * Telegram Bot Configuration
 *
 * Untuk mengatur bot Telegram yang akan mengirim notifikasi
 * hasil pemrosesan surat kepada pengguna
 */

// Make sure constants are loaded
if (!defined('ROOT_DIR')) {
    require_once 'constants.php';
}

// Bot Token dari BotFather
define('TELEGRAM_BOT_TOKEN', '8226806035:AAHLnh4Q6NNfHb-ASoUQPD-GNspmMrovhMU');

// Chat ID untuk testing (opsional, bisa dikosongkan)
// Contoh: '123456789' (chat ID Telegram Anda)
define('TELEGRAM_TEST_CHAT_ID', '1743293318');

// API URL Telegram (built from base URL + bot token)
define('TELEGRAM_BOT_API_URL', 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/');

/**
 * Pengaturan pesan notifikasi
 */
define('TELEGRAM_MESSAGE_APPROVED', "✅ *Permohonan Surat Disetujui*

📋 *Detail Permohonan:*
• Nomor: %REQUEST_ID%
• Jenis Surat: %LETTER_TYPE%
• Tanggal Pengajuan: %REQUEST_DATE%

👤 *Pemohon:*
• Nama: %USER_NAME%
• Email: %USER_EMAIL%

📅 *Status:*
• Status: Disetujui
• Tanggal Persetujuan: %APPROVED_DATE%
• Disetujui Oleh: %ADMIN_NAME%

📎 File PDF surat dapat diunduh melalui dashboard sistem.");

define('TELEGRAM_MESSAGE_REJECTED', "❌ *Permohonan Surat Ditolak*

📋 *Detail Permohonan:*
• Nomor: %REQUEST_ID%
• Jenis Surat: %LETTER_TYPE%
• Tanggal Pengajuan: %REQUEST_DATE%

👤 *Pemohon:*
• Nama: %USER_NAME%
• Email: %USER_EMAIL%

📅 *Status:*
• Status: Ditolak
• Tanggal Penolakan: %REJECTED_DATE%
• Ditolak Oleh: %ADMIN_NAME%

💬 *Catatan:*
%NOTES%

Silakan ajukan permohonan baru jika diperlukan.");

/**
 * Format tanggal untuk pesan
 */
define('TELEGRAM_DATE_FORMAT', 'd/m/Y H:i');

/**
 * Mengaktifkan atau menonaktifkan notifikasi Telegram
 * Set ke true untuk mengaktifkan, false untuk menonaktifkan
 */
define('TELEGRAM_NOTIFICATIONS_ENABLED', true);

/**
 * Debug mode - akan mencatat semua aktivitas bot ke log file
 */
define('TELEGRAM_DEBUG_MODE', true);

/**
 * Path untuk log file Telegram bot
 */
define('TELEGRAM_LOG_FILE', ROOT_DIR . '/logs/telegram_bot.log');
?>
