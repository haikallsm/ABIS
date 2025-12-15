# Laragon Nginx Setup untuk ABIS Project

## Masalah
Website homepage berhasil dimuat, tapi halaman login/register menampilkan 404 karena Nginx tidak menggunakan .htaccess seperti Apache.

## Solusi

### Opsi 1: Konfigurasi Nginx di Laragon (Recommended)

1. **Buka Laragon** dan stop semua service
2. **Klik Menu** → **Nginx** → **sites-enabled**
3. **Buat file baru**: `abis.conf`
4. **Copy paste** konfigurasi berikut:

```nginx
server {
    listen 80;
    server_name localhost;

    root "D:/laragon/www/ABIS_PBP";
    index index.php;

    # Handle PHP files
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Handle static files
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Main routing - send everything to index.php
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Security headers
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Prevent access to sensitive files
    location ~ /(config|database|session)\.php$ {
        deny all;
        return 403;
    }

    location ~ /\.(htaccess|htpasswd|env)$ {
        deny all;
        return 403;
    }
}
```

5. **Save file** dan restart Laragon
6. **Test**: `http://localhost/ABIS_PBP/login`

### Opsi 2: Ganti ke Apache (Alternatif)

1. **Klik kanan** di Laragon tray icon
2. **Pilih** "Apache" instead of "Nginx"
3. **Restart** Laragon
4. **Test**: `http://localhost/ABIS_PBP/login`

### Opsi 3: Gunakan PHP Built-in Server (Development Only)

```bash
cd D:\laragon\www\ABIS_PBP
php -S localhost:8080 -t .
```

Kemudian akses: `http://localhost:8080/login`

## Troubleshooting

### Jika masih 404:
1. Pastikan file `nginx.conf` atau `abis.conf` tersimpan dengan benar
2. Restart Laragon completely
3. Check Laragon logs di menu tray

### Jika PHP tidak berjalan:
1. Pastikan PHP service running di Laragon
2. Check port 9000 untuk FastCGI

## Testing

Setelah setup benar, semua URL berikut harus bekerja:
- `http://localhost/ABIS_PBP/` - Homepage
- `http://localhost/ABIS_PBP/login` - Login page
- `http://localhost/ABIS_PBP/register` - Register page
- `http://localhost/ABIS_PBP/admin/dashboard` - Admin dashboard

