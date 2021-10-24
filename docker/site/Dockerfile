FROM php:7.0-apache

RUN docker-php-ext-install mbstring mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN a2enmod rewrite
