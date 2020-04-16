# telepanorama

### Поставка

    docker build --file="./deployment/telepanorama/nginx/Dockerfile" --tag="telepanorama-nginx-image:0.0.1" "./deployment/empty"
    docker build --file="./deployment/telepanorama/decorator/php/Dockerfile" --build-arg SITE_MODE=dev --tag="decorator-php-image:0.0.1" "./decorator"
    docker build --file="./deployment/telepanorama/decorator/php/Dockerfile" --build-arg SITE_MODE=prod --tag="decorator-php-image:0.0.1" "./decorator"
    docker build --file="./deployment/telepanorama/decorator/nginx/Dockerfile" --tag="decorator-nginx-image:0.0.1" "./deployment/empty"
    docker build --file="./deployment/telepanorama/decorator/jobber/Dockerfile" --tag="decorator-jobber-image:0.0.1" "./deployment/telepanorama/decorator/jobber"
    docker build --file="./deployment/telepanorama/showcase/nginx/Dockerfile" --tag="showcase-nginx-image:0.0.1" "./showcase"
    
    docker swarm init
    docker stack deploy --compose-file="docker-compose.yml" telepanorama-stack
    docker stack ls
    docker stack services telepanorama-stack
    docker stack rm telepanorama-stack
    
    docker exec -ti $(docker ps -q --filter NAME=showcase-nginx) bash
    docker exec -ti $(docker ps -q --filter NAME=decorator-php) bash
    docker exec -ti $(docker ps -q --filter NAME=decorator-jobber) bash
    
    docker service logs telepanorama-stack_decorator-jobber
    docker service logs telepanorama-stack_decorator-nginx
    docker service logs telepanorama-stack_decorator-php
    
### Запуск тестов

    # для просмотра отчёта о покрытии тестами открыть с диска ./decorator/coverage/index.html в браузере
    docker exec -ti $(docker ps -q --filter NAME=decorator-php) ./vendor/bin/phpunit tests 
    docker exec -ti $(docker ps -q --filter NAME=decorator-php) ./vendor/bin/phpunit tests --no-coverage
    
### Настройка PHPSTORM

    добавить SSH в образ PHP
    
        RUN apt-get update && \
            apt-get install -y ssh && \
            chsh -s /bin/bash www-data && \
            echo 'www-data:ssh-password' | chpasswd
            
        CMD service ssh start && php-fpm
        
    пробросить в контнейнере порт 22 в docker-compose.yml
    
        ports:
          - 2201:22
          
    добавить сервер SFTP через File | Settings | Build, Execution, Deployment | Deployment
    
 ### Настройка облака
 
    apt-get update
    apt-get install apt-transport-https 
    apt-get install ca-certificates 
    apt-get install curl 
    apt-get install gnupg-agent 
    apt-get install software-properties-common
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
    add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
    apt-get update
    sudo apt-get install docker-ce docker-ce-cli containerd.io
    docker -v
    systemctl enable docker
    apt-get install git
    cd /root
    ssh-keygen -t rsa -b 4096 -C "mem-memov@yandex.ru"
    cat .ssh/id_rsa.pub
    git clone git@github.com:mem-memov/telepanorama.git
    cd telepanorama
    docker swarm init
    docker stack deploy --compose-file="docker-compose.yml" telepanorama-stack
    docker stack ls
    systemctl stop ufw
    
    
    