-- Surat-In - Sistem Pengelolaan Surat Database Schema
-- Created: December 2025
-- Updated: December 2025

-- Users table - menyimpan data pengguna dan admin
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    phone VARCHAR(20),
    address TEXT,
    telegram_chat_id VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Letter types table - menyimpan jenis-jenis surat yang tersedia
CREATE TABLE letter_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) NOT NULL UNIQUE,
    description TEXT,
    template_path VARCHAR(255),
    required_fields JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Letter requests table - menyimpan pengajuan surat dari pengguna
-- request_data: data utama dalam format JSON
-- additional_data: data tambahan spesifik jenis surat dalam format JSON
CREATE TABLE letter_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    letter_type_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    request_data JSON,
    additional_data JSON,
    letter_number VARCHAR(100) NULL,
    generated_file VARCHAR(255),
    admin_notes TEXT,
    approved_at TIMESTAMP NULL,
    approved_by INT NULL,
    rejected_at TIMESTAMP NULL,
    rejected_by INT NULL,
    telegram_sent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (letter_type_id) REFERENCES letter_types(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (rejected_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table - menyimpan konfigurasi sistem
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@surat-in.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert sample users for testing
INSERT INTO users (username, email, password, full_name, role, phone, address, telegram_chat_id) VALUES
('user1', 'user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User Test 1', 'user', '081234567890', 'Jl. Test No. 1', '1743293318'),
('user2', 'user2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User Test 2', 'user', '081234567891', 'Jl. Test No. 2', NULL);

-- Insert sample letter types (9 jenis surat)
INSERT INTO letter_types (name, code, description, required_fields) VALUES
('Surat Keterangan Domisili', 'SKD', 'Surat keterangan domisili untuk keperluan administrasi', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "keperluan": "Keperluan", "alamat_domisili": "Alamat Domisili"}'),
('Surat Keterangan Usaha', 'SKU', 'Surat keterangan usaha untuk keperluan bisnis', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "nama_usaha": "Nama Usaha", "jenis_usaha": "Jenis Usaha", "alamat_usaha": "Alamat Usaha", "keperluan": "Keperluan"}'),
('Surat Pengantar Nikah', 'SPN', 'Surat pengantar nikah untuk keperluan pernikahan', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "nik_pasangan": "NIK Pasangan", "nama_pasangan": "Nama Pasangan", "keperluan": "Keperluan"}'),
('Surat Keterangan Tidak Mampu', 'SKTM', 'Surat keterangan tidak mampu untuk keperluan bantuan sosial', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "penghasilan": "Penghasilan", "keperluan": "Keperluan"}'),
('Surat Keterangan', 'SK', 'Surat keterangan umum untuk berbagai keperluan', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "keperluan": "Keperluan"}'),
('Surat Keterangan Belum Menikah', 'SKBM', 'Surat keterangan belum menikah untuk keperluan administrasi', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "keperluan": "Keperluan"}'),
('Surat Rekomendasi Beasiswa', 'SRB', 'Surat rekomendasi beasiswa untuk keperluan pendidikan', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "sekolah": "Asal Sekolah/Kampus", "nis_nim": "NIS/NIM", "jurusan": "Jurusan/Program Studi", "semester": "Semester", "nama_beasiswa": "Nama Beasiswa", "nama_ayah": "Nama Ayah/Wali", "keperluan": "Keperluan"}'),
('Surat Izin Usaha', 'SIU', 'Surat izin usaha untuk keperluan bisnis', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "nama_usaha": "Nama Usaha", "jenis_usaha": "Jenis Usaha", "alamat_usaha": "Alamat Usaha", "keperluan": "Keperluan"}'),
('Surat Izin Kegiatan', 'SIK', 'Surat izin kegiatan untuk keperluan acara', '{"nama": "Nama Lengkap", "nik": "NIK", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "jenis_kelamin": "Jenis Kelamin", "agama": "Agama", "pekerjaan": "Pekerjaan", "alamat": "Alamat", "nama_kegiatan": "Nama Kegiatan", "tanggal_kegiatan": "Tanggal Kegiatan", "waktu_kegiatan": "Waktu Kegiatan", "tempat_kegiatan": "Tempat Kegiatan", "hiburan": "Hiburan/Entertainment", "keperluan": "Keperluan"}');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('telegram_bot_token', '8226806035:AAHLnh4Q6NNfHb-ASoUQPD-GNspmMrovhMU', 'string', 'Telegram Bot Token untuk notifikasi'),
('telegram_test_chat_id', '1743293318', 'string', 'Chat ID Telegram untuk testing'),
('telegram_notifications_enabled', '1', 'boolean', 'Aktifkan/nonaktifkan notifikasi Telegram'),
('telegram_debug_mode', '1', 'boolean', 'Mode debug untuk logging Telegram'),
('site_name', 'Surat-In - Sistem Pengelolaan Surat', 'string', 'Nama aplikasi'),
('site_description', 'Sistem pengelolaan surat menyurat desa secara digital', 'string', 'Deskripsi aplikasi'),
('admin_email', 'admin@surat-in.id', 'string', 'Email administrator'),
('max_file_size', '10485760', 'integer', 'Maksimal ukuran file upload dalam bytes (10MB)'),
('allowed_file_types', '["pdf"]', 'json', 'Tipe file yang diizinkan untuk upload'),
('letter_number_prefix', 'REG', 'string', 'Prefix untuk nomor surat'),
('letter_number_year', YEAR(CURDATE()), 'integer', 'Tahun untuk nomor surat');

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_telegram_chat_id ON users(telegram_chat_id);
CREATE INDEX idx_users_is_active ON users(is_active);
CREATE INDEX idx_letter_requests_user_id ON letter_requests(user_id);
CREATE INDEX idx_letter_requests_status ON letter_requests(status);
CREATE INDEX idx_letter_requests_letter_type_id ON letter_requests(letter_type_id);
CREATE INDEX idx_letter_requests_created_at ON letter_requests(created_at);
CREATE INDEX idx_letter_requests_approved_at ON letter_requests(approved_at);
CREATE INDEX idx_letter_requests_telegram_sent ON letter_requests(telegram_sent);
CREATE INDEX idx_letter_types_code ON letter_types(code);
CREATE INDEX idx_letter_types_is_active ON letter_types(is_active);
CREATE INDEX idx_settings_key ON settings(setting_key);

-- Composite indexes for common queries
CREATE INDEX idx_letter_requests_user_status ON letter_requests(user_id, status);
CREATE INDEX idx_letter_requests_status_created ON letter_requests(status, created_at);
CREATE INDEX idx_letter_requests_type_status ON letter_requests(letter_type_id, status);

-- Insert sample letter requests for testing
INSERT INTO letter_requests (user_id, letter_type_id, status, request_data, additional_data, admin_notes, telegram_sent, created_at) VALUES
(2, 1, 'approved', '{"nama": "User Test 1", "nik": "1234567890123456", "tempat_lahir": "Jakarta", "tanggal_lahir": "1990-01-01", "jenis_kelamin": "Laki-laki", "agama": "Islam", "pekerjaan": "Mahasiswa", "alamat": "Jl. Test No. 1", "keperluan": "Untuk keperluan akademik", "alamat_domisili": "Jl. Test No. 1"}', '{}', 'Approved by system', 1, NOW()),
(3, 7, 'pending', '{"nama": "User Test 2", "nik": "1234567890123457", "tempat_lahir": "Bandung", "tanggal_lahir": "1992-05-15", "jenis_kelamin": "Perempuan", "agama": "Islam", "pekerjaan": "Pelajar", "alamat": "Jl. Test No. 2", "sekolah": "SMA Negeri 1", "nis_nim": "123456", "jurusan": "IPA", "semester": "3", "nama_beasiswa": "Beasiswa Unggulan", "nama_ayah": "Ayah User", "keperluan": "Untuk pengajuan beasiswa"}', '{}', NULL, 0, NOW()),
(2, 3, 'rejected', '{"nama": "User Test 1", "nik": "1234567890123456", "tempat_lahir": "Jakarta", "tanggal_lahir": "1990-01-01", "jenis_kelamin": "Laki-laki", "agama": "Islam", "pekerjaan": "Mahasiswa", "alamat": "Jl. Test No. 1", "nik_pasangan": "1234567890123458", "nama_pasangan": "Calon Pasangan", "keperluan": "Untuk keperluan pernikahan"}', '{}', 'Dokumen tidak lengkap', 1, DATE_SUB(NOW(), INTERVAL 2 DAY));
