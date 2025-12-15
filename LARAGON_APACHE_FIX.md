# LARAGON: Fix Login/Register 404 Error

## Masalah
- `http://localhost/ABIS_PBP/login` → 404 Error
- `http://localhost:8000/login` (PHP built-in) → Works ✅

## Penyebab
Laragon menggunakan **Nginx** yang tidak mendukung `.htaccess` untuk URL rewriting.

## Solusi: Ganti ke Apache

### Step-by-Step Instructions:

#### 1. **Stop All Services**
```bash
# Klik kanan Laragon tray icon → Stop All
```

#### 2. **Switch to Apache**
```bash
# Klik kanan Laragon tray icon
# Pilih "Apache" (bukan "Nginx")
# Tunggu hingga status berubah ke "Apache"
```

#### 3. **Start Services**
```bash
# Klik kanan Laragon tray icon → Start All
# Tunggu hingga Apache dan MySQL running
```

#### 4. **Verify Web Server**
```bash
# Klik kanan Laragon tray icon
# Icon harus menampilkan "Apache" (bukan "Nginx")
```

#### 5. **Test URLs**
```bash
# Buka browser dan test:
http://localhost/ABIS_PBP/          # Homepage ✅
http://localhost/ABIS_PBP/login     # Login page ✅
http://localhost/ABIS_PBP/register  # Register page ✅
http://localhost/ABIS_PBP/admin/dashboard  # Admin ✅
```

## Jika Masih Bermasalah

### Alternative: Configure Nginx

1. **Copy konfigurasi** dari `nginx.conf` ke Laragon nginx sites
2. **Restart Nginx** di Laragon
3. **Test kembali**

### Alternative: Use PHP Built-in Server

```bash
cd D:\laragon\www\ABIS_PBP
php -S localhost:8080 -t .
# Access: http://localhost:8080/login
```

## Expected Result

Setelah mengikuti langkah di atas:
- ✅ Apache akan running (bukan Nginx)
- ✅ `.htaccess` akan bekerja untuk URL rewriting
- ✅ Semua routing `/login`, `/register`, dll akan berfungsi
- ✅ Sistem ABIS fully functional

## Troubleshooting

### Jika Apache tidak bisa start:
1. **Check Windows Services** - Pastikan tidak ada conflict
2. **Change Port** - Jika port 80 conflict, ubah di Laragon settings
3. **Reinstall Laragon** - Jika corrupt

### Jika masih 404 setelah ganti ke Apache:
1. **Check .htaccess** - Pastikan file ada di root directory
2. **Check Apache modules** - Pastikan mod_rewrite enabled
3. **Check error logs** - Lihat Laragon logs

## Final Status

Setelah fix ini, sistem ABIS akan berjalan normal di:
- ✅ `http://localhost/ABIS_PBP/` (Apache)
- ❌ `http://localhost:8000/` (PHP built-in - alternative only)

