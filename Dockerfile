FROM php:7-apache

RUN apt update && apt install -y libicu-dev
RUN docker-php-ext-install intl pdo_mysql