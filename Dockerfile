# Dockerfile
FROM php:8.2-fpm

# Gerekli paketler ve PostgreSQL için pdo_pgsql
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip git curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Composer'ı ekle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Çalışma dizini
WORKDIR /var/www

# Laravel dosyalarını ekle
COPY . .

# Bağımlılıkları yükle
RUN composer install

# Gerekli izinler
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 9000
CMD ["php-fpm"]
