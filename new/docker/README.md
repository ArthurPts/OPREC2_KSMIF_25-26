# Docker Setup untuk Laravel Application

Dokumentasi lengkap untuk menjalankan aplikasi Laravel ini menggunakan Docker.

## Struktur Docker

```
docker/
├── Dockerfile          # Multi-stage build untuk PHP-FPM dan Nginx
├── start.sh           # Script startup untuk PHP-FPM container
├── nginx/
│   └── nginx.conf     # Konfigurasi Nginx
└── README.md          # Dokumentasi ini
```

## Arsitektur

Aplikasi ini menggunakan arsitektur multi-container:

1. **app-php**: Container PHP-FPM 8.2 untuk menjalankan aplikasi Laravel
2. **app-nginx**: Container Nginx sebagai web server dan reverse proxy

### Multi-Stage Build

Dockerfile menggunakan 4 stage untuk optimasi:

1. **composer-builder**: Build dependencies PHP dengan Composer
2. **assets**: Build frontend assets dengan Node.js dan Vite
3. **php-fpm**: Runtime PHP-FPM dengan aplikasi siap produksi
4. **nginx**: Web server Nginx dengan static files

## Prasyarat

- Docker Engine 20.10+
- Docker Compose V2+
- File `.env` sudah dikonfigurasi

## Quick Start

### 1. Persiapan Environment

```bash
# Copy dan edit file environment
cp .env.example .env

# Edit konfigurasi database dan aplikasi
nano .env
```

### 2. Build dan Jalankan

```bash
# Build images
docker compose build

# Jalankan containers
docker compose up -d

# Cek status containers
docker compose ps

# Lihat logs
docker compose logs -f
```

### 3. Akses Aplikasi

Aplikasi dapat diakses di: `http://localhost:8080`

## Konfigurasi Environment

Pastikan konfigurasi berikut di file `.env`:

```env
# Application
APP_NAME="Laravel App"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8080

# Database
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Cache & Session
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

## Perintah Docker Compose

### Manajemen Container

```bash
# Jalankan containers
docker compose up -d

# Stop containers
docker compose stop

# Restart containers
docker compose restart

# Hapus containers
docker compose down

# Hapus containers dan volumes
docker compose down -v
```

### Build & Update

```bash
# Rebuild images tanpa cache
docker compose build --no-cache

# Rebuild dan restart
docker compose up -d --build

# Pull image terbaru
docker compose pull
```

### Logs & Debugging

```bash
# Lihat semua logs
docker compose logs

# Follow logs real-time
docker compose logs -f

# Logs specific service
docker compose logs -f app-php
docker compose logs -f app-nginx

# Lihat 100 baris terakhir
docker compose logs --tail=100
```

### Eksekusi Perintah dalam Container

```bash
# Masuk ke PHP container
docker compose exec app-php sh

# Jalankan artisan commands
docker compose exec app-php php artisan migrate
docker compose exec app-php php artisan cache:clear
docker compose exec app-php php artisan config:cache
docker compose exec app-php php artisan route:cache
docker compose exec app-php php artisan view:cache

# Jalankan composer
docker compose exec app-php composer install
docker compose exec app-php composer update

# Masuk ke Nginx container
docker compose exec app-nginx sh
```

## Volumes

### Volume Persistent

- `app-data`: Menyimpan kode aplikasi (shared antara PHP dan Nginx)

### Volume Bind Mounts

- `./public/images`: Upload images dari user
- `./storage/app/public`: Public storage files

## Network

Container berjalan di network `app-net` yang terisolasi. Komunikasi antar container:

- **app-nginx** → **app-php**: Port 9000 (PHP-FPM)
- **Host** → **app-nginx**: Port 8080 (HTTP)

## Health Checks

### PHP-FPM Container
- Test: `php-fpm -t`
- Interval: 10s
- Start period: 10s

### Nginx Container
- Test: `wget http://localhost/`
- Interval: 10s
- Start period: 5s

## Troubleshooting

### Container tidak bisa start

```bash
# Cek logs untuk error
docker compose logs app-php
docker compose logs app-nginx

# Cek status container
docker compose ps

# Restart containers
docker compose restart
```

### Permission Issues

```bash
# Pastikan ownership benar di host
sudo chown -R 1000:1000 public/images storage/app/public

# Atau adjust PUID/PGID di docker-compose.yml
```

### Database Connection Failed

```bash
# Pastikan database sudah running
# Cek environment variables
docker compose exec app-php env | grep DB_

# Test koneksi database
docker compose exec app-php php artisan db:show
```

### Rebuild untuk Update Code

```bash
# Rebuild images dengan code terbaru
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Clear Cache Laravel

```bash
# Clear semua cache
docker compose exec app-php php artisan optimize:clear

# Clear cache tertentu
docker compose exec app-php php artisan cache:clear
docker compose exec app-php php artisan config:clear
docker compose exec app-php php artisan route:clear
docker compose exec app-php php artisan view:clear
```

## Production Best Practices

1. **Security**
   - Jangan expose port langsung ke internet
   - Gunakan reverse proxy (Traefik/Nginx Proxy Manager)
   - Set APP_DEBUG=false
   - Gunakan HTTPS

2. **Performance**
   - Enable OPcache (sudah dikonfigurasi)
   - Cache configuration: `php artisan config:cache`
   - Cache routes: `php artisan route:cache`
   - Cache views: `php artisan view:cache`

3. **Monitoring**
   - Monitor logs: `docker compose logs -f`
   - Monitor resources: `docker stats`
   - Setup alerting untuk container down

4. **Backup**
   - Backup volumes: `docker run --rm -v app-data:/data -v $(pwd):/backup alpine tar czf /backup/app-data.tar.gz /data`
   - Backup database secara terpisah

## Integrasi dengan Reverse Proxy

Untuk production, tambahkan container ini ke network reverse proxy:

```yaml
networks:
  app-net:
    name: app-net
    driver: bridge
  rp-net:
    name: rp-net
    external: true
```

Dan tambahkan network ke service nginx:

```yaml
services:
  app-nginx:
    networks:
      - app-net
      - rp-net
```

## Environment Variables

### PHP Container

| Variable | Default | Description |
|----------|---------|-------------|
| RUN_MIGRATIONS | true | Jalankan migrasi saat startup |
| APP_ENV | production | Environment aplikasi |
| APP_DEBUG | false | Debug mode |

### Build Arguments

| Argument | Default | Description |
|----------|---------|-------------|
| PUID | 1000 | User ID untuk www-data |
| PGID | 1000 | Group ID untuk www-data |

## Support

Untuk masalah atau pertanyaan, silakan:
1. Cek dokumentasi Laravel: https://laravel.com/docs
2. Cek dokumentasi Docker: https://docs.docker.com
3. Review logs: `docker compose logs`
