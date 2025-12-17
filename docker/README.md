# Docker Deployment Guide - Surat-In

Panduan lengkap untuk menjalankan aplikasi Surat-In menggunakan Docker.

## 📋 Prerequisites

- Docker Engine 20.10+
- Docker Compose 2.0+
- Git
- Minimal 2GB RAM untuk semua container

## 🚀 Quick Start

### Development Environment

1. **Clone repository:**
```bash
git clone <repository-url>
cd ABIS_PBP
```

2. **Start development environment:**
```bash
cd docker
docker-compose up -d
```

3. **Akses aplikasi:**
- **Aplikasi**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **MailHog** (email testing): http://localhost:8025

### Production Environment

1. **Setup environment variables:**
```bash
cp docker/env.docker .env
# Edit .env dengan konfigurasi production
```

2. **Start production environment:**
```bash
cd docker
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

3. **Setup SSL (optional):**
```bash
# Jalankan certbot untuk SSL
docker-compose -f docker-compose.yml -f docker-compose.prod.yml exec certbot certonly --webroot -w /var/www/certbot --email admin@surat-in.id --agree-tos --no-eff-email -d yourdomain.com
```

## 🏗️ Architecture

```
surat-in-network
├── app (PHP 8.1 + Apache/Nginx)
├── db (MySQL 8.0)
├── phpmyadmin (Database management)
├── redis (Caching - optional)
└── nginx (Web server - production)
```

## 📁 File Structure

```
docker/
├── Dockerfile              # Apache + PHP untuk development
├── Dockerfile.fpm          # PHP-FPM untuk production
├── docker-compose.yml      # Development stack
├── docker-compose.override.yml  # Development overrides
├── docker-compose.prod.yml # Production stack
├── nginx.conf             # Nginx configuration
├── php.ini               # PHP configuration
├── env.docker            # Environment template
└── README.md             # This file
```

## ⚙️ Configuration

### Environment Variables

Salin dan edit file `docker/env.docker`:

```bash
# Database
DB_HOST=db
DB_NAME=surat_in_db
DB_USER=surat_user
DB_PASS=your_secure_password

# Telegram Bot
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_TEST_CHAT_ID=your_chat_id

# Application
APP_ENV=production
APP_URL=https://yourdomain.com
```

### Database

Database akan otomatis diinisialisasi dengan:
- Schema lengkap dari `database/schema.sql`
- User default: `admin` / password: `password`
- Sample data untuk testing

### File Uploads

Upload directory akan persistent di Docker volume:
- **Development**: `./docker/volumes/uploads`
- **Production**: Docker named volume `uploads`

## 🔧 Management Commands

### Start/Stop Services

```bash
# Development
docker-compose up -d
docker-compose down

# Production
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
docker-compose -f docker-compose.yml -f docker-compose.prod.yml down
```

### View Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f db
```

### Database Management

```bash
# Connect to MySQL
docker-compose exec db mysql -u surat_user -p surat_in_db

# Backup database
docker-compose exec db mysqldump -u surat_user -p surat_in_db > backup.sql

# Restore database
docker-compose exec -T db mysql -u surat_user -p surat_in_db < backup.sql
```

### Update Application

```bash
# Pull latest changes
git pull

# Rebuild containers
docker-compose build --no-cache

# Restart services
docker-compose up -d
```

## 🔒 Security Considerations

### Production Setup

1. **Change default passwords** di environment variables
2. **Enable SSL/HTTPS** menggunakan certbot
3. **Configure firewall** untuk port yang diperlukan
4. **Regular backup** database dan files
5. **Monitor logs** untuk aktivitas mencurigakan

### Environment Variables

Jangan commit file `.env` ke repository:

```bash
# .gitignore
.env
docker/volumes/
```

## 📊 Monitoring

### Health Checks

```bash
# Check service health
curl http://localhost:8080/health

# Check PHP-FPM status
curl http://localhost:8080/php-status
```

### Resource Usage

```bash
# View container resources
docker stats

# View logs
docker-compose logs -f --tail=100
```

## 🐛 Troubleshooting

### Common Issues

1. **Port already in use:**
```bash
# Change ports in docker-compose.yml
ports:
  - "8082:80"  # Instead of 8080
```

2. **Permission issues:**
```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data /var/www/html
```

3. **Database connection failed:**
```bash
# Check database logs
docker-compose logs db

# Restart database
docker-compose restart db
```

4. **File upload issues:**
```bash
# Check upload directory permissions
docker-compose exec app ls -la /var/www/html/public/uploads
```

## 📈 Scaling

### Horizontal Scaling

Untuk traffic tinggi, scale aplikasi:

```bash
# Scale app containers
docker-compose up -d --scale app=3

# Load balancer diperlukan untuk multiple instances
```

### Database Optimization

```sql
-- Enable query cache
SET GLOBAL query_cache_size = 268435456;
SET GLOBAL query_cache_type = 1;
```

## 🔄 Backup & Restore

### Automated Backup

```bash
# Create backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
docker-compose exec db mysqldump -u surat_user -p surat_in_db > backup_$DATE.sql
docker run --rm -v surat-in_uploads:/data -v $(pwd):/backup alpine tar czf /backup/uploads_$DATE.tar.gz -C /data .
```

### Restore

```bash
# Restore database
docker-compose exec -T db mysql -u surat_user -p surat_in_db < backup.sql

# Restore uploads
docker run --rm -v surat-in_uploads:/data -v $(pwd):/backup alpine tar xzf /backup/uploads.tar.gz -C /data
```

## 📞 Support

Untuk bantuan lebih lanjut:
- Check logs: `docker-compose logs`
- View container status: `docker-compose ps`
- Access container: `docker-compose exec app bash`
