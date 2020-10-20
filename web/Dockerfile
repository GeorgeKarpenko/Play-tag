FROM php:7.4-fpm

RUN apt-get update && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql
RUN pecl install redis && docker-php-ext-enable redis


