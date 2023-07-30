FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        libpq-dev && \
        docker-php-ext-install pdo_pgsql

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

# TODO: fix and uncomment this lines
# COPY . .
# RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader
# RUN php src/Console/CreateTablesMigrationCommand.php

CMD ["php-fpm"]
