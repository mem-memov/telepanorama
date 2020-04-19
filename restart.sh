docker stack rm telepanorama-stack

docker build --file="./deployment/telepanorama/showcase/nginx/Dockerfile" --tag="showcase-nginx-image:0.0.1" "./showcase"

docker build --file="./deployment/telepanorama/decorator/php/Dockerfile" --build-arg SITE_MODE=prod --tag="decorator-php-image:0.0.1" "./decorator"

docker stack deploy --compose-file="docker-compose.yml" telepanorama-stack

docker service logs -f telepanorama-stack_decorator-jobber
