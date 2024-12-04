# Используем официальный образ PHP с поддержкой FPM
FROM php:8.0-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочего каталога
WORKDIR /var/www

# Копируем файлы проекта
COPY . .

# Установка зависимостей Laravel
RUN composer install

# Копируем файл конфигурации php
COPY ./php.ini /usr/local/etc/php/

# Права доступа
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache