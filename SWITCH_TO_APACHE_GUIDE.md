# PANDUAN: Ganti Nginx ke Apache di Laragon

## Masalah
```
404 Not Found
nginx/1.22.0
```

Laragon menggunakan **Nginx** yang tidak mendukung `.htaccess` untuk URL rewriting.

## Solusi: Ganti ke Apache

### Langkah 1: Stop All Services
```
Klik kanan icon Laragon di system tray
→ "Stop All"
```

### Langkah 2: Switch to Apache
```
Klik kanan icon Laragon di system tray
→ "Apache" (pilih opsi Apache, bukan Nginx)
```

### Langkah 3: Tunggu Status Berubah
```
Icon Laragon akan berubah dari "Nginx" ke "Apache"
Tunggu hingga status menunjukkan Apache running
```

### Langkah 4: Start Services
```
Klik kanan icon Laragon di system tray
→ "Start All"
```

### Langkah 5: Verify Apache Running
```
- Icon Laragon menampilkan "Apache"
- Buka Laragon menu → Services
- Pastikan Apache: "Running", Nginx: "Stopped"
```

## Testing Setelah Switch

### Akses URL dengan benar:
```
✅ http://localhost/ABIS_PBP/          → Homepage
✅ http://localhost/ABIS_PBP/login     → Login page
✅ http://localhost/ABIS_PBP/register  → Register page
✅ http://localhost/ABIS_PBP/dashboard → User dashboard
```

## Jika Masih Bermasalah

### Option 1: Restart Laragon Completely
```
1. Exit Laragon completely (klik kanan → Exit)
2. Start Laragon lagi
3. Pilih Apache
4. Start All
```

### Option 2: Manual Service Management
```
Laragon Menu → Services → Apache → Start
Laragon Menu → Services → Nginx → Stop
```

### Option 3: Check Port Conflicts
```
Jika Apache gagal start, mungkin port 80 conflict:
Laragon Menu → Preferences → Apache → Port → Ubah ke 8080
```

## Troubleshooting

### Error: "Apache cannot start"
```
1. Check Windows Services untuk Apache conflict
2. Change Apache port di Laragon preferences
3. Restart Windows jika perlu
```

### Error: Still showing Nginx
```
1. Pastikan Nginx stopped completely
2. Restart Laragon
3. Check menu tray untuk status
```

## Konfirmasi Sukses

Setelah berhasil switch ke Apache:
- ❌ Tidak ada lagi "nginx/1.22.0" di error pages
- ✅ Semua URL routing bekerja normal
- ✅ .htaccess file diakui oleh Apache
- ✅ PHP routing system berfungsi

## Penting!

**Jangan gunakan PHP built-in server** (`php -S`) untuk development ini karena tidak support subdirectory routing dengan benar.

**Gunakan Apache di Laragon** untuk environment yang sesuai dengan production!</content>
</xai:function_call=SWITCH_TO_APACHE_GUIDE.md

