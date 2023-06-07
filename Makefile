include .env
export

docker := $(shell command -v docker 2> /dev/null)
docker_compose := $(shell command -v docker-compose -f docker-compose.yml -p se 2> /dev/null)
php_container = $(PROJECT_NAME)-php-fpm


# Запускает все необходимое для страта проекта
init: up install
	@echo 'http://localhost:$(NGINX_HOST_HTTP_PORT)'

# Поднимает докер
up:
	$(docker_compose) up -d

# Выключает докер
down:
	$(docker_compose) down

# Перезапускает докер
restart:
	$(docker_compose) down
	$(docker_compose) up -d

# composer install
install:
	$(docker) exec $(php_container) sh -c "composer install"

