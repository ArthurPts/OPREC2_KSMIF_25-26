# Quick Start - Docker Setup

Dokumentasi lengkap ada di `docker/README.md`

## Setup Cepat

```bash
# 1. Setup environment
cp .env.example .env
nano .env  # Edit sesuai kebutuhan

# 2. Build dan jalankan
./docker/helper.sh build
./docker/helper.sh up

# 3. Akses aplikasi
# http://localhost:8080
```

## Perintah Umum

```bash
# Manajemen Container
./docker/helper.sh up          # Start containers
./docker/helper.sh down        # Stop containers
./docker/helper.sh restart     # Restart containers
./docker/helper.sh logs        # Lihat logs

# Laravel Commands
./docker/helper.sh artisan migrate
./docker/helper.sh artisan cache:clear
./docker/helper.sh composer install

# Database
./docker/helper.sh migrate     # Run migrations
./docker/helper.sh seed        # Run seeders
./docker/helper.sh fresh       # Reset database

# Cache Management
./docker/helper.sh cache clear    # Clear cache
./docker/helper.sh cache create   # Create cache

# Development
./docker/helper.sh shell       # Masuk ke PHP container
./docker/helper.sh test        # Run tests
```

## Struktur Docker

```
docker/
├── Dockerfile          # Multi-stage build
├── docker-compose.yml  # Container orchestration
├── start.sh           # PHP-FPM startup script
├── helper.sh          # Helper commands
├── nginx/
│   └── nginx.conf     # Nginx configuration
└── README.md          # Dokumentasi lengkap
```

## Troubleshooting

**Container tidak start?**
```bash
./docker/helper.sh logs
```

**Permission error?**
```bash
sudo chown -R 1000:1000 public/images storage/app/public
```

**Rebuild semuanya?**
```bash
./docker/helper.sh rebuild
```

**Lihat bantuan lengkap?**
```bash
./docker/helper.sh help
```

---

📖 **Dokumentasi lengkap**: `docker/README.md`
