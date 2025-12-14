#!/bin/bash

# Helper script untuk Docker commands
set -e

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_DIR"

# Colors untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function untuk print dengan warna
print_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Function untuk cek apakah .env ada
check_env() {
    if [ ! -f .env ]; then
        print_warning ".env file tidak ditemukan!"
        if [ -f .env.example ]; then
            print_info "Copying .env.example ke .env..."
            cp .env.example .env
            print_warning "Silakan edit .env file sesuai kebutuhan Anda"
            return 1
        else
            print_error ".env.example juga tidak ditemukan!"
            return 1
        fi
    fi
    return 0
}

# Commands
case "${1}" in
    build)
        print_info "Building Docker images..."
        docker compose build --no-cache
        print_info "Build selesai!"
        ;;
    
    up|start)
        check_env || exit 1
        print_info "Starting containers..."
        docker compose up -d
        print_info "Containers started!"
        print_info "Aplikasi dapat diakses di: http://localhost:8080"
        ;;
    
    down|stop)
        print_info "Stopping containers..."
        docker compose down
        print_info "Containers stopped!"
        ;;
    
    restart)
        print_info "Restarting containers..."
        docker compose restart
        print_info "Containers restarted!"
        ;;
    
    rebuild)
        print_info "Rebuilding dan restarting containers..."
        docker compose down
        docker compose build --no-cache
        docker compose up -d
        print_info "Rebuild selesai!"
        ;;
    
    logs)
        SERVICE="${2:-}"
        if [ -z "$SERVICE" ]; then
            docker compose logs -f
        else
            docker compose logs -f "$SERVICE"
        fi
        ;;
    
    ps|status)
        docker compose ps
        ;;
    
    exec)
        SERVICE="${2:-app-php}"
        COMMAND="${3:-sh}"
        docker compose exec "$SERVICE" "$COMMAND"
        ;;
    
    artisan)
        shift
        docker compose exec app-php php artisan "$@"
        ;;
    
    composer)
        shift
        docker compose exec app-php composer "$@"
        ;;
    
    migrate)
        print_info "Running migrations..."
        docker compose exec app-php php artisan migrate --force
        print_info "Migrations selesai!"
        ;;
    
    seed)
        print_info "Running seeders..."
        docker compose exec app-php php artisan db:seed --force
        print_info "Seeding selesai!"
        ;;
    
    fresh)
        print_warning "Ini akan reset database dan menjalankan migrations!"
        read -p "Apakah Anda yakin? (y/N) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            print_info "Running migrate:fresh..."
            docker compose exec app-php php artisan migrate:fresh --force
            print_info "Database berhasil di-reset!"
        else
            print_info "Dibatalkan."
        fi
        ;;
    
    cache)
        ACTION="${2:-clear}"
        if [ "$ACTION" = "clear" ]; then
            print_info "Clearing cache..."
            docker compose exec app-php php artisan optimize:clear
            print_info "Cache cleared!"
        else
            print_info "Caching configuration..."
            docker compose exec app-php php artisan config:cache
            docker compose exec app-php php artisan route:cache
            docker compose exec app-php php artisan view:cache
            print_info "Cache created!"
        fi
        ;;
    
    test)
        print_info "Running tests..."
        docker compose exec app-php php artisan test
        ;;
    
    shell)
        SERVICE="${2:-app-php}"
        print_info "Opening shell in $SERVICE container..."
        docker compose exec "$SERVICE" sh
        ;;
    
    clean)
        print_warning "Ini akan menghapus containers dan volumes!"
        read -p "Apakah Anda yakin? (y/N) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            print_info "Cleaning up..."
            docker compose down -v
            print_info "Cleanup selesai!"
        else
            print_info "Dibatalkan."
        fi
        ;;
    
    help|*)
        echo "Docker Helper Script"
        echo ""
        echo "Usage: ./docker/helper.sh [command] [options]"
        echo ""
        echo "Commands:"
        echo "  build                Build Docker images"
        echo "  up, start            Start containers"
        echo "  down, stop           Stop containers"
        echo "  restart              Restart containers"
        echo "  rebuild              Rebuild dan restart containers"
        echo "  logs [service]       Show logs (optional: specify service)"
        echo "  ps, status           Show container status"
        echo "  exec [service] [cmd] Execute command in container"
        echo "  artisan [args]       Run artisan command"
        echo "  composer [args]      Run composer command"
        echo "  migrate              Run migrations"
        echo "  seed                 Run seeders"
        echo "  fresh                Reset database dan run migrations"
        echo "  cache [clear|create] Clear atau create cache"
        echo "  test                 Run tests"
        echo "  shell [service]      Open shell in container"
        echo "  clean                Remove containers dan volumes"
        echo "  help                 Show this help"
        echo ""
        echo "Examples:"
        echo "  ./docker/helper.sh build"
        echo "  ./docker/helper.sh up"
        echo "  ./docker/helper.sh logs app-php"
        echo "  ./docker/helper.sh artisan migrate"
        echo "  ./docker/helper.sh composer install"
        echo "  ./docker/helper.sh shell"
        ;;
esac
