# telepanorama

### Поставка

    docker build --file="./deployment/telepanorama/nginx/Dockerfile" --tag="telepanorama-nginx-image:0.0.1" "./deployment/empty"
    docker build --file="./deployment/telepanorama/decorator/php/Dockerfile" --tag="decorator-php-image:0.0.1" "./decorator"
    docker build --file="./deployment/telepanorama/decorator/nginx/Dockerfile" --tag="decorator-nginx-image:0.0.1" "./deployment/empty"
    docker build --file="./deployment/telepanorama/showcase/nginx/Dockerfile" --tag="showcase-nginx-image:0.0.1" "./showcase"
    docker swarm init
    docker stack deploy --compose-file="docker-compose.yml" telepanorama-stack
    docker stack ls
    docker stack services telepanorama-stack
    docker stack rm telepanorama-stack