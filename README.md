# ABIS - Aplikasi Desa Digital

ABIS (Aplikasi Desa Digital) adalah sistem pengelolaan surat menyurat desa yang terintegrasi secara digital. Aplikasi ini dibangun menggunakan PHP Native dengan arsitektur MVC sederhana dan TailwindCSS untuk styling modern.

## 🚀 Fitur Utama

### User Features
- ✅ Registrasi dan login user
- ✅ Dashboard user dengan daftar jenis surat
- ✅ Pengajuan surat dengan form dinamis
- ✅ Generate surat otomatis (PDF/DOCX)
- ✅ Download dokumen yang telah di-generate
- ✅ Monitoring status permohonan (pending/approved/rejected)

### Admin Features
- ✅ Login admin terpisah
- ✅ Dashboard admin dengan statistik lengkap
- ✅ Kelola user (lihat, hapus akun)
- ✅ Approval/reject permohonan surat
- ✅ Download file surat yang sudah dibuat
- ✅ Integrasi Telegram Bot untuk notifikasi

### Sistem Features
- ✅ Role-based authentication (admin/user)
- ✅ Session management dengan middleware
- ✅ Responsive design dengan TailwindCSS
- ✅ RESTful routing sederhana
- ✅ Database MySQL dengan foreign key
- ✅ File upload system
- ✅ CSRF protection

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP Native (tanpa framework)
- **Frontend**: HTML5, TailwindCSS v4 CLI
- **Database**: MySQL 5.7+
- **Server**: Laragon + Nginx
- **Styling**: TailwindCSS dengan custom components
- **Icons**: Font Awesome 6
- **JavaScript**: Vanilla JS dengan utilities

## 📋 Prerequisites

Sebelum menjalankan aplikasi ini, pastikan Anda memiliki:

- PHP 8.1 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Node.js 16+ (untuk TailwindCSS CLI)
- Composer (opsional, untuk dependency management)
- Laragon (recommended untuk Windows) atau web server lainnya

## 🚀 Quick Setup (Recommended)

### Otomatis Setup (1 Command)
```bash
git clone <repository-url> ABIS
cd ABIS
chmod +x setup.sh && ./setup.sh
```

### Manual Setup
Lihat panduan lengkap di [`SETUP_GUIDE.md`](./SETUP_GUIDE.md)

### Development Server
```bash
# Start semua services
./start.sh

# Atau manual:
npm run dev & php -S localhost:8000 index.php
```

### Testing
```bash
# Jalankan semua test
./test.sh
```

### Production Deployment
```bash
# Deploy to production
./deploy.sh production

# Backup database
./backup.sh
```

## 🚀 Instalasi dan Setup

### ⚡ Quick Setup (3 Menit - Recommended)

```bash
# 1. Clone repository
git clone <repository-url> ABIS
cd ABIS

# 2. Setup otomatis (database, dependencies, permissions)
chmod +x setup.sh && ./setup.sh

# 3. Jalankan development server
./start.sh

# 4. Akses aplikasi
open http://localhost:8000
```

### 📖 Manual Setup (Detail)
Untuk setup manual step-by-step, lihat [`SETUP_GUIDE.md`](./SETUP_GUIDE.md)

### 🧪 Testing Setup
```bash
# Test semua komponen
./test.sh
```

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/abis-aplikasi-desa-digital.git
cd abis-aplikasi-desa-digital
```

### 2. Setup Database

1. Buat database MySQL baru:
   ```sql
   CREATE DATABASE abis_desa_digital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Import schema database:
   ```bash
   mysql -u root -p abis_desa_digital < database/schema.sql
   ```

### 3. Setup TailwindCSS

```bash
# Install dependencies
npm install

# Build CSS untuk production
npm run build

# Atau development mode (watch changes)
npm run dev
```

### 4. Konfigurasi Web Server

#### Menggunakan Laragon + Nginx:

1. Pastikan project berada di folder `D:\laragon\www\ABIS_PBP`
2. Start Laragon dan pastikan Nginx + MySQL aktif
3. Akses aplikasi di: `http://localhost/ABIS_PBP`

#### Konfigurasi Manual (Apache/Nginx):

**Apache (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name localhost;
    root /path/to/abis;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 5. Konfigurasi Environment

Edit file `config/constants.php` jika diperlukan:

```php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'abis_desa_digital');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL (sesuaikan dengan setup Anda)
define('BASE_URL', 'http://localhost/ABIS_PBP');
```

## 🎯 Cara Menjalankan

### Development Mode

```bash
# Terminal 1: Jalankan TailwindCSS watcher
npm run dev

# Terminal 2: Jalankan PHP server (jika tidak menggunakan Laragon)
php -S localhost:8000
```

### Production Mode

```bash
# Build CSS untuk production
npm run build

# Setup web server seperti di atas
```

## 📁 Struktur Folder

```
ABIS_PBP/
├── app/                          # Application logic
│   ├── controllers/              # Controllers (MVC)
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── AdminController.php
│   │   └── HomeController.php
│   ├── models/                   # Models (MVC)
│   │   ├── User.php
│   │   ├── LetterRequest.php
│   │   └── LetterType.php
│   └── views/                    # Views (MVC)
│       ├── layouts/              # Layout templates
│       │   ├── main.php
│       │   ├── header.php
│       │   └── footer.php
│       ├── home/
│       │   └── index.php
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       ├── user/
│       │   └── dashboard.php
│       ├── admin/
│       │   └── dashboard.php
│       └── errors/
│           └── 404.php
├── config/                       # Configuration files
│   ├── config.php               # Main config
│   ├── database.php             # Database config
│   ├── session.php              # Session config
│   └── constants.php            # Constants
├── database/                    # Database files
│   └── schema.sql               # Database schema
├── public/                      # Public assets
│   ├── assets/
│   │   ├── css/
│   │   │   ├── style.css        # Compiled TailwindCSS
│   │   │   ├── homepage.css     # Homepage styles
│   │   │   ├── auth.css         # Auth styles
│   │   │   └── dashboard.css    # Dashboard styles
│   │   └── js/
│   │       ├── app.js           # Main JavaScript
│   │       └── homepage.js      # Homepage scripts
│   └── uploads/                 # File uploads
├── resources/                   # Source files
│   └── css/
│       └── input.css            # TailwindCSS input
├── utils/                       # Utility files
├── index.php                    # Entry point
├── package.json                 # Node.js dependencies
├── tailwind.config.js          # TailwindCSS config
└── README.md                   # Documentation
```

## 🔐 Akun Default

### Admin Account
- **Username**: admin
- **Password**: admin123
- **Role**: Administrator

### User Account
Daftar akun user baru melalui halaman register, atau buat manual di database.

## 🔧 Konfigurasi Tambahan

### Telegram Bot Setup

1. Buat bot di Telegram via @BotFather
2. Dapatkan BOT_TOKEN dan CHAT_ID
3. Update di database:
   ```sql
   UPDATE settings SET setting_value = 'YOUR_BOT_TOKEN' WHERE setting_key = 'telegram_bot_token';
   UPDATE settings SET setting_value = 'YOUR_CHAT_ID' WHERE setting_key = 'telegram_chat_id';
   ```

### File Upload Configuration

Edit `config/constants.php`:
```php
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx']);
```

## 📊 API Endpoints

### Authentication
- `GET /login` - Login page
- `POST /login` - Process login
- `GET /register` - Register page
- `POST /register` - Process register
- `POST /logout` - Logout

### User Routes
- `GET /dashboard` - User dashboard
- `GET /requests/create` - Create request form
- `POST /requests/create` - Process create request
- `GET /requests/:id` - View request details
- `GET /requests/:id/download` - Download generated file

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - User management
- `POST /admin/users/:id/delete` - Delete user
- `GET /admin/requests` - Request management
- `POST /admin/requests/:id/approve` - Approve request
- `POST /admin/requests/:id/reject` - Reject request

## 🐛 Troubleshooting

### Error: Class not found
Pastikan semua file controller dan model sudah di-include dengan benar di `index.php`.

### Error: Database connection failed
Periksa konfigurasi database di `config/database.php`.

### Error: TailwindCSS not compiling
Pastikan Node.js terinstall dan jalankan `npm install`.

### Error: File upload failed
Periksa permission folder `public/uploads/` dan konfigurasi PHP upload.

## 🔧 Development Tools

Project ini menyediakan beberapa script helper untuk memudahkan development workflow:

### Setup & Installation
```bash
./setup.sh          # Setup otomatis (database, dependencies, environment)
./start.sh          # Start development servers (PHP + TailwindCSS)
```

### Testing & Quality Assurance
```bash
./test.sh           # Jalankan semua test (PHP syntax, database, dependencies)
```

### Production & Deployment
```bash
./deploy.sh production    # Deploy ke production environment
./deploy.sh staging       # Deploy ke staging environment
./backup.sh               # Backup database dan files
```

### File Konfigurasi
- `SETUP_GUIDE.md` - Panduan setup lengkap untuk tim development
- `env.example` - Template file environment configuration
- `package.json` - Node.js dependencies dan scripts
- `tailwind.config.js` - Konfigurasi TailwindCSS

### Quick Development Commands
```bash
# Development
./start.sh              # Start all dev servers
npm run dev            # CSS watch mode only
php -S localhost:8000 index.php  # PHP server only

# Testing
./test.sh              # Run all tests
php -l app/controllers/*.php  # Check PHP syntax

# Production
./deploy.sh production # Deploy to production
./backup.sh           # Database backup
```

## 🤝 Contributing

1. Fork repository
2. Buat branch feature baru (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 👥 Tim Developer

- **Lead Developer**: ABIS Development Team
- **Project**: Aplikasi Desa Digital
- **Version**: 1.0.0

## 📞 Support

Untuk support atau pertanyaan, silakan buat issue di repository ini atau hubungi tim developer.

---

---

## 📋 Quick Start Checklist

### ✅ Pre-setup Requirements
- [x] PHP 8.1+ installed
- [x] MySQL 5.7+ installed
- [x] Node.js 16+ installed
- [x] Git installed
- [x] Laragon (Windows) or web server configured

### 🚀 One-Command Setup
```bash
git clone <repository-url> ABIS && cd ABIS && ./setup.sh
```

### 🎯 Development Workflow
```bash
# Start development
./start.sh

# Test changes
./test.sh

# Deploy to production
./deploy.sh production

# Backup data
./backup.sh
```

### 📱 Access Points
- **Homepage:** `http://localhost:8000`
- **Admin Login:** `http://localhost:8000/auth/login`
- **Admin Dashboard:** `http://localhost:8000/admin/dashboard`
- **Surat Pengantar:** `http://localhost:8000/admin/requests`

### 👤 Default Accounts
- **Admin:** `admin` / `password`
- **Email:** `admin@abisdesa.id`

---

**ABIS - Aplikasi Desa Digital** © 2025. Made with ❤️ by ABIS Development Team.
