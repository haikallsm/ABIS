# Telegram Bot Setup Guide

Panduan untuk mengatur bot Telegram untuk notifikasi sistem ABIS.

## 📋 Persiapan

### 1. Buat Bot Telegram
1. Buka aplikasi Telegram
2. Cari **@BotFather**
3. Kirim pesan `/newbot`
4. Ikuti instruksi untuk membuat bot baru
5. **Simpan BOT TOKEN** yang diberikan BotFather

### 2. Dapatkan Chat ID (Opsional, untuk testing)
1. Kirim pesan ke bot yang baru dibuat
2. Akses URL: `https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates`
3. Cari `"chat":{"id":123456789` dalam response
4. **Simpan CHAT ID** tersebut

## ⚙️ Konfigurasi

### 1. Salin File Template
```bash
cp config/telegram.php.example config/telegram.php
```

### 2. Edit File .env
Buka file `.env` di root project dan tambahkan:

```bash
# Telegram Bot Configuration
TELEGRAM_BOT_TOKEN=8226806035:AAHLnh4Q6NNfHb-ASoUQPD-GNspmMrovhMU
TELEGRAM_TEST_CHAT_ID=1743293318
```

**Catatan:** File `config/telegram.php` akan otomatis membaca nilai dari `.env` file.

### 3. Aktifkan Notifikasi
Pastikan di file konfigurasi:
```php
define('TELEGRAM_NOTIFICATIONS_ENABLED', true);
```

## 🧪 Testing

### Test Koneksi Bot
```bash
php -r "
require_once 'config/config.php';
require_once 'config/telegram.php';
require_once 'utils/TelegramBot.php';

\$bot = new TelegramBot();
\$result = \$bot->testConnection();
print_r(\$result);
"
```

### Test Kirim Pesan
```bash
php -r "
require_once 'config/config.php';
require_once 'config/telegram.php';
require_once 'utils/TelegramBot.php';

\$bot = new TelegramBot();
\$result = \$bot->sendMessage(TELEGRAM_TEST_CHAT_ID, 'Test message from ABIS!');
print_r(\$result);
"
```

## 🔒 Keamanan

- **JANGAN** commit file `config/telegram.php` ke repository
- File tersebut sudah ada di `.gitignore`
- Gunakan file `config/telegram.php.example` sebagai template
- Bot token bersifat sensitif dan harus dijaga kerahasiaannya

## 📝 Fitur Notifikasi

Bot akan mengirim notifikasi otomatis ketika:

1. **Admin menyetujui** permohonan surat
2. **Admin menolak** permohonan surat
3. **PDF surat** dikirim sebagai lampiran (jika tersedia)

## 🚨 Troubleshooting

### Bot Tidak Merespon
- Pastikan BOT TOKEN benar
- Cek koneksi internet
- Verifikasi bot belum dihapus di BotFather

### Pesan Tidak Terkirim
- Pastikan CHAT ID benar
- User harus memulai percakapan dengan bot terlebih dahulu
- Cek log file: `logs/telegram_bot.log`

### PDF Tidak Terkirim
- Pastikan file PDF ada di `generated_pdfs/`
- Cek permission file
- Lihat log untuk error SSL/connection

## 📞 Support

Jika ada masalah, cek:
1. Log file: `logs/telegram_bot.log`
2. Status koneksi bot
3. Konfigurasi server firewall

---

**⚠️ PENTING:** Jaga kerahasiaan BOT TOKEN. Jika terkompromi, segera regenerate token di BotFather.
