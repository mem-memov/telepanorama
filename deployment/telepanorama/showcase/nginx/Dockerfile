FROM nginx:1.17

RUN echo "root-password\nroot-password" | passwd root

RUN mkdir /var/www && \
    chown -R www-data:www-data /var/www

COPY . /var/www

RUN chown www-data:www-data /var/www/*

# SSH for SFTP on PORT 22
RUN apt-get update && \
    apt-get install -y ssh && \
    chsh -s /bin/bash www-data && \
    echo 'www-data:ssh-password' | chpasswd

RUN apt-get update && \
    apt-get install -y nano

CMD service ssh start && nginx -g "daemon off;"