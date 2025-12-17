# Surat-In Docker Image Guide

Panduan lengkap untuk membuat, menggunakan, dan mendistribusikan Docker image aplikasi Surat-In.

## 📦 Build Docker Image

### Build Image Secara Manual

```bash
# Build image untuk development
cd docker
docker build -t surat-in:latest ../

# Build image untuk production (multi-stage)
docker build -f Dockerfile.multi -t surat-in:prod ../

# Build dengan custom tag
docker build -t surat-in:v1.0.0 ../
```

### Build dengan Script (Recommended)

```bash
cd docker

# Build image interaktif
chmod +x build-image.sh
./build-image.sh

# Build dengan tag spesifik
./build-image.sh v1.0.0

# Build untuk registry
./build-image.sh latest myregistry.com/surat-in
```

## 🚀 Menjalankan Docker Image

### Standalone Container (Development)

```bash
# Jalankan container
docker run -d \
  --name surat-in-app \
  -p 8080:80 \
  surat-in:latest

# Akses aplikasi
open http://localhost:8080
```

### Dengan Database

```bash
# Jalankan MySQL container
docker run -d \
  --name surat-in-db \
  -e MYSQL_ROOT_PASSWORD=rootpass \
  -e MYSQL_DATABASE=surat_in_db \
  -e MYSQL_USER=surat_user \
  -e MYSQL_PASSWORD=userpass \
  -p 3306:3306 \
  mysql:8.0

# Jalankan aplikasi dengan environment
docker run -d \
  --name surat-in-app \
  --link surat-in-db:db \
  -e DB_HOST=db \
  -e DB_NAME=surat_in_db \
  -e DB_USER=surat_user \
  -e DB_PASS=userpass \
  -p 8080:80 \
  surat-in:latest
```

### Production Setup dengan Docker Compose

```bash
# Setup production
cd docker
cp env.docker .env
# Edit .env dengan konfigurasi production

# Jalankan production stack
docker-compose -f docker-compose.yml -f docker-compose.prod.multi.yml up -d

# Jalankan dengan load balancer
docker-compose -f docker-compose.yml -f docker-compose.prod.multi.yml -f docker-compose.lb.yml up -d
```

## 🔧 Konfigurasi Environment

### Environment Variables

Buat file `.env` dari template:

```bash
cp docker/env.docker .env
```

Edit `.env` dengan konfigurasi Anda:

```bash
# Database
DB_HOST=db
DB_NAME=surat_in_db
DB_USER=surat_user
DB_PASS=your_secure_password

# Telegram
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_TEST_CHAT_ID=your_chat_id

# Application
APP_ENV=production
APP_URL=https://yourdomain.com
```

### Mount Volumes

```bash
# Mount uploads dan logs
docker run -d \
  --name surat-in-app \
  -v /host/path/uploads:/var/www/html/public/uploads \
  -v /host/path/logs:/var/www/html/logs \
  -p 8080:80 \
  surat-in:latest
```

## 📤 Distribusi Image

### Push ke Docker Registry

```bash
# Tag image untuk registry
docker tag surat-in:latest your-registry.com/surat-in:latest

# Login ke registry
docker login your-registry.com

# Push image
docker push your-registry.com/surat-in:latest
```

### Simpan sebagai File TAR

```bash
# Simpan image sebagai file
docker save surat-in:latest > surat-in-latest.tar

# Load image dari file
docker load < surat-in-latest.tar
```

## 🏭 CI/CD Pipeline

### GitHub Actions Example

```yaml
name: Build and Push Docker Image

on:
  push:
    branches: [ main ]

jobs:
  build:
    runs-on: ubuntu-latest

    steps:
    - uses: actions/checkout@v2

    - name: Build Docker image
      run: |
        cd docker
        docker build -t surat-in:${{ github.sha }} ../

    - name: Push to Registry
      run: |
        echo ${{ secrets.DOCKER_PASSWORD }} | docker login -u ${{ secrets.DOCKER_USERNAME }} --password-stdin
        docker tag surat-in:${{ github.sha }} your-registry.com/surat-in:${{ github.sha }}
        docker push your-registry.com/surat-in:${{ github.sha }}
```

### Jenkins Pipeline

```groovy
pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                script {
                    docker.build("surat-in:${env.BUILD_NUMBER}", "-f docker/Dockerfile.multi .")
                }
            }
        }

        stage('Test') {
            steps {
                script {
                    docker.image("surat-in:${env.BUILD_NUMBER}").inside {
                        sh 'php artisan test'
                    }
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    docker.withRegistry('https://your-registry.com', 'registry-credentials') {
                        docker.image("surat-in:${env.BUILD_NUMBER}").push()
                    }
                }
            }
        }
    }
}
```

## 🌐 Deployment ke VPS

### Automated Deployment

```bash
# Copy files ke server
scp -r docker/ user@your-vps:/path/to/app/

# Jalankan deployment
ssh user@your-vps << EOF
cd /path/to/app/docker
chmod +x deploy.sh
./deploy.sh production latest
EOF
```

### Manual Deployment

1. **Setup VPS:**
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.17.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

2. **Deploy Application:**
```bash
# Clone repository
git clone your-repo-url surat-in
cd surat-in/docker

# Setup environment
cp env.docker .env
nano .env  # Edit dengan konfigurasi production

# Deploy
docker-compose -f docker-compose.yml -f docker-compose.prod.multi.yml up -d

# Setup SSL (optional)
docker-compose -f docker-compose.yml -f docker-compose.prod.multi.yml exec certbot certonly --webroot -w /var/www/certbot --email admin@surat-in.id --agree-tos --no-eff-email -d yourdomain.com
```

3. **Configure Reverse Proxy (Optional):**

```nginx
# /etc/nginx/sites-available/surat-in
server {
    listen 80;
    server_name yourdomain.com;

    location / {
        proxy_pass http://127.0.0.1:80;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

## 📊 Monitoring & Maintenance

### Health Checks

```bash
# Check container health
docker ps
docker stats

# Check application health
curl http://localhost/health

# Check logs
docker-compose logs -f app
docker-compose logs -f db
```

### Backup & Restore

```bash
# Backup database
docker-compose exec db mysqldump -u surat_user -p surat_in_db > backup.sql

# Backup uploads
docker cp $(docker-compose ps -q app):/var/www/html/public/uploads ./backup/uploads

# Restore
docker-compose exec -T db mysql -u surat_user -p surat_in_db < backup.sql
```

### Updates

```bash
# Update application
git pull
docker-compose build --no-cache
docker-compose up -d

# Update dengan zero-downtime
docker-compose up -d --scale app=2
docker-compose up -d --scale app=1
```

## 🐛 Troubleshooting

### Common Issues

1. **Port already in use:**
```bash
# Change port mapping
docker run -p 8081:80 surat-in:latest
```

2. **Permission issues:**
```bash
# Fix volume permissions
docker exec -it surat-in-app chown -R www-data:www-data /var/www/html/public/uploads
```

3. **Database connection failed:**
```bash
# Check database logs
docker-compose logs db

# Verify environment variables
docker exec surat-in-app env | grep DB_
```

4. **Image build failed:**
```bash
# Build with no cache
docker build --no-cache -t surat-in:latest .

# Check build logs
docker build -t surat-in:latest . 2>&1 | tee build.log
```

### Debug Commands

```bash
# Enter container
docker exec -it surat-in-app bash

# Check PHP errors
docker exec surat-in-app tail -f /var/www/html/logs/php_errors.log

# Check database connection
docker exec surat-in-app php -r "
try {
    \$pdo = new PDO('mysql:host=db;dbname=surat_in_db', 'surat_user', 'password');
    echo 'Database connected successfully';
} catch (Exception \$e) {
    echo 'Connection failed: ' . \$e->getMessage();
}
"
```

## 🔒 Security Best Practices

### Image Security

```dockerfile
# Use specific base image versions
FROM php:8.1-fpm-alpine3.16

# Avoid running as root
RUN addgroup -g 1000 www && adduser -D -s /bin/sh -u 1000 -G www www-data
USER www-data

# Minimize attack surface
RUN apk del --no-cache .build-deps
```

### Runtime Security

```bash
# Run containers with limited privileges
docker run --read-only --tmpfs /tmp --security-opt=no-new-privileges surat-in:latest

# Use secrets for sensitive data
echo "db_password" | docker secret create db_password -
```

### Network Security

```yaml
# docker-compose.yml
services:
  app:
    networks:
      - internal
    # No external ports exposed

  nginx:
    ports:
      - "80:80"
      - "443:443"
    networks:
      - internal
      - external

networks:
  internal:
    internal: true
  external:
```

## 📈 Performance Optimization

### Image Optimization

```dockerfile
# Multi-stage build
FROM node:18-alpine AS frontend
# ... build frontend

FROM php:8.1-fpm-alpine AS production
COPY --from=frontend /app/public/assets/build ./public/assets/build
# ... only copy necessary files
```

### Runtime Optimization

```bash
# Resource limits
docker run \
  --memory=512m \
  --cpus=0.5 \
  --restart=unless-stopped \
  surat-in:latest

# Health checks
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost/health || exit 1
```

## 📞 Support

Untuk bantuan lebih lanjut:
- Check logs: `docker-compose logs -f`
- View container status: `docker-compose ps`
- Access container: `docker-compose exec app bash`
- GitHub Issues: [your-repo-url]/issues
