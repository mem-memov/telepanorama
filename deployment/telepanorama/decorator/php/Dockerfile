FROM php:7.4.4-fpm-buster

# prod|dev
ARG SITE_MODE

# GD
RUN apt-get update -y && \
    apt-get install -y --no-install-recommends \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        libjpeg62-turbo-dev && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd

# IMAP
RUN apt-get update && \
    apt-get install -y \
        libc-client-dev libkrb5-dev && \
    rm -r /var/lib/apt/lists/* && \
    docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-install -j$(nproc) imap

# SSH2
RUN apt-get update && \
    apt-get install -y \
        libssh2-1-dev libssh2-1 && \
    pecl install ssh2-1.2 && \
    docker-php-ext-enable ssh2

# XDEBUG
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug
RUN if [[ "$SITE_MODE" == "dev" ]]; \
    then echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini; \
    fi

COPY . /var/www/html

# COMPOSER
RUN apt-get update && \
    apt-get install -y git zip unzip
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer
RUN rm -rf vendor && \
    if [[ "$SITE_MODE" == "prod" ]]; \
    then composer install --prefer-dist --no-scripts -n; \
    else composer install; \
    fi

RUN chown -R www-data:www-data /var/www/html/*

# SSH for SFTP on PORT 22
RUN apt-get update && \
    apt-get install -y ssh && \
    chsh -s /bin/bash www-data && \
    echo 'www-data:ssh-password' | chpasswd

CMD service ssh start && php-fpm
