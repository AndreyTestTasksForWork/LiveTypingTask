FROM php:8-fpm

RUN apt-get update && apt-get install -y \
  cron \
  libfreetype6-dev \
  libicu-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  libpng-dev \
  libxslt1-dev \
  libxml2-dev \
  zip \
  git \
  curl

RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
