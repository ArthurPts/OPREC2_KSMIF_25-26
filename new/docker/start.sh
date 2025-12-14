#!/usr/bin/env sh
set -e

cd /var/www/html

# Jalankan migrasi hanya jika diizinkan (default: true)
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  echo "Running database migrations..."
  php artisan migrate --seed --force || true
fi

# Jalankan PHP-FPM di foreground supaya container tetap hidup
echo "Starting PHP-FPM..."
exec php-fpm -F
