# 1. Use PHP base image
FROM php:8.1-fpm

# 2. Set working directory
WORKDIR /var/www

# 3. Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# 4. Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 5. Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# 6. Copy project files to container
COPY . .

# 7. Install PHP dependencies inside container
RUN composer install --no-dev --optimize-autoloader

# 8. Set permissions
RUN chown -R www-data:www-data /var/www

# 9. Expose port 8000
EXPOSE 8000

# 10. Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
