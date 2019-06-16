FROM php:fpm
RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql
