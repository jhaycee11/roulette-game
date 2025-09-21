FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev zip libzip-dev \
    sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Copy project files
COPY . .

# Set up Laravel
RUN echo 'APP_NAME="Roulette Game"' > .env \
    && echo 'APP_ENV=production' >> .env \
    && echo 'APP_KEY=' >> .env \
    && echo 'APP_DEBUG=false' >> .env \
    && echo 'APP_TIMEZONE=UTC' >> .env \
    && echo 'APP_URL=http://localhost' >> .env \
    && echo 'DB_CONNECTION=sqlite' >> .env \
    && echo 'DB_DATABASE=/var/www/database/database.sqlite' >> .env \
    && echo 'SESSION_DRIVER=database' >> .env \
    && echo 'CACHE_STORE=database' >> .env \
    && echo 'QUEUE_CONNECTION=database' >> .env \
    && php artisan key:generate --force \
    && php artisan storage:link \
    && mkdir -p storage/framework/{cache,views,sessions} bootstrap/cache public/storage/save \
    && chmod -R 775 storage bootstrap/cache \
    && touch database/database.sqlite \
    && php artisan migrate --force

# Expose port
EXPOSE 8000

# Default command
CMD ["php", "-S", "0.0.0.0:${PORT:-8000}", "-t", "public"]
