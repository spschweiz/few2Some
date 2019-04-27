FROM php:7.2-apache
MAINTAINER Till Kleisli <till@kleisli.ch>

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install utilities used by TYPO3 CMS / Flow / Neos
RUN apt-get update && \
    apt-get install -y \
		graphicsmagick \
		git \
		zip \
		unzip \
		wget \
		curl \
		libpng-dev \
		libxml2-dev \
		zlib1g-dev \
		libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev

RUN docker-php-ext-install mysqli soap zip opcache
RUN pecl install apcu \
  && docker-php-ext-enable apcu

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

# Enable apache modules
RUN a2enmod rewrite headers

RUN docker-php-ext-install pdo_mysql

EXPOSE 80
EXPOSE 443