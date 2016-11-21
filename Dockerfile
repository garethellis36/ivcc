FROM php:7-apache

RUN apt update && apt install -y libicu-dev
RUN a2enmod rewrite
RUN docker-php-ext-install intl pdo_mysql