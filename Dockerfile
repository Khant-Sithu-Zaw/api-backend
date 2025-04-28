# Use PHP-FPM as the base image
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    nginx \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    echo "gd extension configured successfully" && \
    docker-php-ext-install gd
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel application into the container
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy Nginx config
COPY ./nginx/default.conf /etc/nginx/sites-available/default

# Expose PHP-FPM and HTTP port (80)
EXPOSE 9000 80

# Start PHP-FPM and Nginx together
CMD service nginx start && php-fpm
