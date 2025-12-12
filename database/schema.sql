-- ABIS - Aplikasi Desa Digital Database Schema
-- Created: December 2025

-- Create database
CREATE DATABASE IF NOT EXISTS abis_desa_digital
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE abis_desa_digital;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Letter types table
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

-- Letter requests table
CREATE TABLE letter_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    letter_type_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    request_data JSON,
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

-- Settings table for system configuration
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
('admin', 'admin@abisdesa.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert sample letter types
INSERT INTO letter_types (name, code, description, required_fields) VALUES
('Surat Keterangan Domisili', 'SKD', 'Surat keterangan domisili untuk keperluan administrasi', '{"nik": "NIK", "nama": "Nama Lengkap", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "alamat": "Alamat Lengkap", "keperluan": "Keperluan"}'),
('Surat Keterangan Usaha', 'SKU', 'Surat keterangan usaha untuk keperluan bisnis', '{"nik": "NIK", "nama": "Nama Lengkap", "alamat_usaha": "Alamat Usaha", "jenis_usaha": "Jenis Usaha", "lama_usaha": "Lama Usaha"}'),
('Surat Pengantar Nikah', 'SPN', 'Surat pengantar nikah untuk keperluan pernikahan', '{"nik": "NIK", "nama": "Nama Lengkap", "tempat_lahir": "Tempat Lahir", "tanggal_lahir": "Tanggal Lahir", "alamat": "Alamat", "nama_pasangan": "Nama Calon Pasangan"}'),
('Surat Keterangan Tidak Mampu', 'SKTM', 'Surat keterangan tidak mampu untuk keperluan bantuan sosial', '{"nik": "NIK", "nama": "Nama Lengkap", "alamat": "Alamat", "pekerjaan": "Pekerjaan", "penghasilan": "Penghasilan Bulanan"}');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('telegram_bot_token', '', 'string', 'Telegram Bot Token untuk notifikasi'),
('telegram_chat_id', '', 'string', 'Chat ID Telegram untuk admin notifications'),
('site_name', 'ABIS - Aplikasi Desa Digital', 'string', 'Nama aplikasi'),
('site_description', 'Sistem pengelolaan surat menyurat desa secara digital', 'string', 'Deskripsi aplikasi'),
('admin_email', 'admin@abisdesa.id', 'string', 'Email administrator'),
('max_file_size', '5242880', 'integer', 'Maksimal ukuran file upload dalam bytes (5MB)'),
('allowed_file_types', '["pdf", "doc", "docx"]', 'json', 'Tipe file yang diizinkan untuk upload');

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_letter_requests_user_id ON letter_requests(user_id);
CREATE INDEX idx_letter_requests_status ON letter_requests(status);
CREATE INDEX idx_letter_requests_created_at ON letter_requests(created_at);
CREATE INDEX idx_letter_types_code ON letter_types(code);
CREATE INDEX idx_settings_key ON settings(setting_key);
