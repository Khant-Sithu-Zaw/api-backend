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
    && rm -rf /var/lib/apt/lists/*  # Clean up apt cache

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    echo "gd extension configured successfully" && \
    docker-php-ext-install gd
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Set working directory
WORKDIR /var/www

# Copy the Laravel app into the container
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose the PHP-FPM port
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
