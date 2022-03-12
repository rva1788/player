FROM php:8.0-fpm

ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install -y \
             libfreetype6-dev \
             libjpeg62-turbo-dev \
             libmcrypt-dev \
             libpng-dev \
             zlib1g-dev \
             libxml2-dev \
             libzip-dev \
             libonig-dev \
             graphviz \
             libcurl4-openssl-dev \
             pkg-config \
             libpq-dev \
             wget \
             nano \
             git

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install intl
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install opcache
RUN docker-php-source delete
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-source delete

RUN echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20200930/xdebug.so" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9000" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.discover_client_host=0" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" | tee -a /usr/local/etc/php/conf.d/xdebug.ini

RUN wget https://getcomposer.org/installer -O - -q \
    | php -- --install-dir=/bin --filename=composer --quiet

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

WORKDIR /var/www/testing
