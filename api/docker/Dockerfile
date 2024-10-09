FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    sqlite-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_sqlite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .
RUN composer install --no-interaction --optimize-autoloader
