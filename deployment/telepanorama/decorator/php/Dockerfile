FROM php:7.4.4-fpm-buster

# IMAP
RUN apt-get update && \
    apt-get install -y \
        libc-client-dev libkrb5-dev && \
    rm -r /var/lib/apt/lists/*
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-install -j$(nproc) imap

COPY . /var/www/html

# COMPOSER
RUN apt-get update && \
    apt-get install -y git zip unzip
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer
RUN composer install

RUN chown www-data:www-data /var/www/html/*

# SSH for DEV SFTP on PORT 22
RUN apt-get update && \
    apt-get install -y ssh && \
    chsh -s /bin/bash www-data && \
    echo 'www-data:ssh-password' | chpasswd

CMD service ssh start && php-fpm
