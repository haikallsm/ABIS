# Fix Database untuk Login & Register

## Masalah
- Login dan register tidak bisa karena database tidak sesuai
- Field `nik` tidak ada di table `users`
- Method `nikExists()` tidak ada di User model

## Solusi Lengkap

### Step 1: Jalankan Migration untuk Menambah Field NIK

```sql
-- Jalankan di phpMyAdmin atau MySQL command line
-- File: database/migration_add_nik.sql

USE abis_desa_digital;

-- Add nik column to users table
ALTER TABLE users ADD COLUMN nik VARCHAR(16) UNIQUE AFTER full_name;

-- Add index for better performance
CREATE INDEX idx_users_nik ON users(nik);
```

### Step 2: Tambahkan User Test

```sql
-- Jalankan setelah migration
-- File: database/add_test_users.sql

USE abis_desa_digital;

-- Update admin user with NIK
UPDATE users SET nik = '1234567890123456' WHERE username = 'admin';

-- Add test users
INSERT INTO users (username, email, password, full_name, nik, role, phone, address) VALUES
('274822291798567', 'warga1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmad Surya', '2748222917985671', 'user', '081234567890', 'Jl. Raya No. 1, Desa Penglipuran'),
('987654321098765', 'warga2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Aminah', '9876543210987654', 'user', '081987654321', 'Jl. Mawar No. 5, Desa Penglipuran'),
('111111111111111', 'warga3@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso', '1111111111111111', 'user', '082123456789', 'Jl. Melati No. 10, Desa Penglipuran');
```

### Step 3: Test Login

Setelah migration, Anda bisa login dengan akun berikut:

#### Admin Account:
- **Username/NIK**: `1234567890123456` atau `admin`
- **Password**: `password`

#### Test User Accounts:
- **NIK**: `2748222917985671`, **Password**: `password`
- **NIK**: `9876543210987654`, **Password**: `password`
- **NIK**: `1111111111111111`, **Password**: `password`

### Step 4: Test Register

Untuk register user baru:
1. Buka `http://localhost/ABIS_PBP/register`
2. Isi form dengan data valid:
   - NIK: 16 digit angka (contoh: `2222222222222222`)
   - Nama: Nama lengkap
   - Email: email@domain.com
   - Phone: nomor HP
   - Password: minimal 8 karakter
3. Submit form

### Cara Import via phpMyAdmin:

1. **Buka phpMyAdmin** (klik menu Laragon)
2. **Pilih database** `abis_desa_digital`
3. **Klik tab "SQL"**
4. **Copy paste** isi file `migration_add_nik.sql`
5. **Klik "Go"**
6. **Ulangi** untuk file `add_test_users.sql`

### Verification:

Setelah migration, cek di phpMyAdmin:
```sql
DESCRIBE users;
```
Harus menampilkan kolom `nik` VARCHAR(16) UNIQUE.

### Troubleshooting:

#### Jika Error "Duplicate entry":
```sql
-- Cek data existing
SELECT * FROM users WHERE nik IS NOT NULL;

-- Hapus jika perlu
DELETE FROM users WHERE nik = 'existing_value';
```

#### Jika Error "Column not found":
```sql
-- Pastikan migration sudah dijalankan
SHOW COLUMNS FROM users LIKE 'nik';
```

## Hasil Akhir:

Setelah mengikuti langkah di atas:
- ✅ **Login** akan berfungsi
- ✅ **Register** akan berfungsi
- ✅ **Database** sesuai dengan aplikasi
- ✅ **Authentication** bekerja normal

