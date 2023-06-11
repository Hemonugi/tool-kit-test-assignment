FROM php:8.2.6-fpm

# Dependecies
RUN apt-get update && apt-get install -y libzip-dev libpq-dev \
    && docker-php-ext-install zip pdo_pgsql

# Install redis
RUN pecl install redis && docker-php-ext-enable redis

# Install xdebug
RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer