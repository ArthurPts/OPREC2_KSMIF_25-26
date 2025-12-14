#############################
# A: Composer builder
#############################
FROM php:8.2-cli AS composer-builder

WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    libpq-dev \
 && docker-php-ext-install pdo_mysql pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files untuk caching dependencies
COPY composer.json composer.lock ./
RUN composer install \
    --no-interaction \
    --no-dev \
    --prefer-dist \
    --no-ansi \
    --no-progress \
    --optimize-autoloader \
    --no-scripts

# Copy source code dan jalankan composer scripts
COPY . .
RUN composer install \
    --no-interaction \
    --no-dev \
    --prefer-dist \
    --no-ansi \
    --no-progress \
    --optimize-autoloader

# Generate application key
RUN php artisan key:generate

#############################
# B: Asset builder (Vite)
#############################
FROM node:20-alpine AS assets

WORKDIR /app

# Copy package files untuk caching dependencies
COPY package.json package-lock.json ./

# Install npm dependencies
RUN npm ci

# Copy files yang dibutuhkan untuk build
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources

# Build assets dengan Vite
RUN npm run build

#############################
# C: PHP-FPM (runtime)
#############################
FROM php:8.2-fpm AS php-fpm

WORKDIR /var/www/html

# Set UID dan GID untuk www-data user
ARG PUID=1000
ARG PGID=1000
RUN groupmod -o -g ${PGID} www-data \
 && usermod -o -u ${PUID} www-data

# Install PHP extensions yang dibutuhkan
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpq-dev \
 && docker-php-ext-install pdo_mysql pdo_pgsql zip opcache \
 && rm -rf /var/lib/apt/lists/*

# Copy optimized opcache configuration
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Copy aplikasi dari builder
COPY --from=composer-builder --chown=${PUID}:${PGID} /app /var/www/html

# Copy build assets dari builder
COPY --from=assets --chown=${PUID}:${PGID} /app/public/build /var/www/html/public/build

# Create storage link
RUN php artisan storage:link

# Set permissions
RUN chmod +x docker/start.sh \
 && [ -d bootstrap/cache ] && find bootstrap/cache -type d -exec chmod 775 {} \; || true \
 && [ -d bootstrap/cache ] && find bootstrap/cache -type f -exec chmod 664 {} \; || true \
 && [ -d storage ] && find storage -type d -exec chmod 775 {} \; || true \
 && [ -d storage ] && find storage -type f -exec chmod 664 {} \; || true \
 && chmod 644 artisan

CMD ["docker/start.sh"]

#############################
# D: Nginx
#############################
FROM nginx:1.25-alpine AS nginx

# Copy public directory dan build assets
COPY --from=composer-builder /app/public /var/www/html/public
COPY --from=assets /app/public/build /var/www/html/public/build

EXPOSE 80
