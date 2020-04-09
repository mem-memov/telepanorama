FROM nginx:1.17

COPY . /usr/share/nginx/html

RUN chown www-data:www-data /usr/share/nginx/html/*

# SSH for SFTP on PORT 22
RUN apt-get update && \
    apt-get install -y ssh && \
    chsh -s /bin/bash www-data && \
    echo 'www-data:ssh-password' | chpasswd