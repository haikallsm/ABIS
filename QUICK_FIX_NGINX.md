# Quick Fix untuk Masalah Nginx 404

## Masalah
Laragon menggunakan Nginx yang tidak mendukung .htaccess, sehingga routing PHP tidak bekerja.

## Solusi Cepat - Ganti ke Apache

### Langkah-langkah:

1. **Klik kanan** pada icon Laragon di system tray
2. **Pilih** "Apache" dari menu (bukan "Nginx")
3. **Tunggu** hingga service restart
4. **Test** website kembali

### Verifikasi:
- Icon Laragon akan berubah menampilkan "Apache"
- Website sekarang menggunakan Apache yang mendukung .htaccess
- Semua routing `/login`, `/register`, dll akan bekerja

### Jika Tidak Bisa:
1. **Stop** semua service di Laragon
2. **Buka** Laragon menu → Preferences → Services
3. **Pastikan** Apache enabled, Nginx disabled
4. **Start** ulang Laragon

## Alternative - Gunakan PHP Built-in Server

```bash
# Di command prompt, navigate ke project folder
cd D:\laragon\www\ABIS_PBP

# Start PHP server
php -S localhost:8080 -t .

# Akses di browser
http://localhost:8080/login
```

## Hasil yang Diharapkan

Setelah mengikuti langkah di atas:
- ✅ `http://localhost/ABIS_PBP/` - Homepage works
- ✅ `http://localhost/ABIS_PBP/login` - Login page works
- ✅ `http://localhost/ABIS_PBP/register` - Register page works
- ✅ Semua routing bekerja normal

