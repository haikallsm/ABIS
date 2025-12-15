# Cara Menggunakan PHP Built-in Server

## Masalah
`POST http://localhost:8000/ABIS_PBP/login 404 (Not Found)`

## Penyebab
PHP built-in server tidak handle subdirectory routing seperti Apache/Nginx.

## Cara Benar Menggunakan PHP Built-in Server

### Step 1: Navigate ke Project Directory
```bash
cd D:\laragon\www\ABIS_PBP
```

### Step 2: Start PHP Server
```bash
php -S localhost:8000 -t .
```

### Step 3: Akses dengan URL yang Benar

#### ❌ SALAH (subdir approach):
```
http://localhost:8000/ABIS_PBP/login
http://localhost:8000/ABIS_PBP/register
```

#### ✅ BENAR (direct access):
```
http://localhost:8000/login
http://localhost:8000/register
http://localhost:8000/admin/dashboard
```

## Mengapa?

Ketika menjalankan `php -S localhost:8000` dari dalam folder `ABIS_PBP`, server menganggap folder tersebut sebagai document root. Jadi:

- `/login` = `D:\laragon\www\ABIS_PBP\login` (tapi di-handle oleh routing)
- `/ABIS_PBP/login` = `D:\laragon\www\ABIS_PBP\ABIS_PBP\login` (file tidak ada)

## Alternative: Gunakan Apache

Untuk development yang lebih realistis, gunakan Apache di Laragon:

1. **Klik kanan** Laragon tray icon
2. **Pilih "Apache"** (bukan "Nginx")
3. **Restart** Laragon
4. **Akses**: `http://localhost/ABIS_PBP/login`

## Testing URLs

### Dengan Apache (Laragon):
```
http://localhost/ABIS_PBP/
http://localhost/ABIS_PBP/login
http://localhost/ABIS_PBP/register
```

### Dengan PHP Built-in Server:
```
http://localhost:8000/
http://localhost:8000/login
http://localhost:8000/register
```

## Kesimpulan

**Gunakan Apache di Laragon** untuk development yang lebih mudah dan sesuai dengan production environment!

