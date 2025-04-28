# 1. Use PHP 8.1 FPM image
FROM php:8.1-fpm

# 2. Set working directory inside container
WORKDIR /var/www

# 3. Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql zip

# 4. Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Copy project files into the container
COPY . .

# 6. Install PHP dependencies inside container
RUN composer install --optimize-autoloader --no-dev

# 7. Set permissions (optional, recommended)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# 8. Expose port 8000 if needed (optional for Laravel serve command)
EXPOSE 8000

# 9. Start PHP-FPM server (or you can CMD another command if needed)
CMD ["php-fpm"]
