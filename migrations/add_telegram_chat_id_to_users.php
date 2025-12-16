<?php
/**
 * Migration: Add telegram_chat_id column to users table
 *
 * Menambahkan kolom telegram_chat_id untuk menyimpan Chat ID Telegram pengguna
 * yang akan digunakan untuk mengirim notifikasi bot
 */

require_once 'config/database.php';

try {
    // Add telegram_chat_id column to users table
    $sql = "ALTER TABLE users ADD COLUMN telegram_chat_id VARCHAR(50) NULL AFTER email";

    $pdo = getDBConnection();
    $pdo->exec($sql);

    echo "✅ Migration berhasil: Kolom telegram_chat_id berhasil ditambahkan ke tabel users\n";

    // Create index for better performance
    $indexSql = "CREATE INDEX idx_users_telegram_chat_id ON users(telegram_chat_id)";
    $pdo->exec($indexSql);

    echo "✅ Index untuk telegram_chat_id berhasil dibuat\n";

} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "⚠️  Kolom telegram_chat_id sudah ada, migration dilewati\n";
    } else {
        echo "❌ Migration gagal: " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "🎉 Migration selesai!\n";
?>
