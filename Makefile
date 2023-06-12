include .env
export

docker_compose := $(shell command -v docker-compose -f docker-compose.yml -p se 2> /dev/null)

# Запускает все необходимое для старта проекта
init: up install create-test-db migrate load-fixtures
	./docker/cli/php bin/console lexik:jwt:generate-keypair --overwrite
	@echo '-------------------------'
	@echo '| http://localhost:$(NGINX_HOST_HTTP_PORT)/api/doc |'
	@echo '-------------------------'

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
	./docker/cli/composer install

check-code:
	@./docker/cli/phpstan analyze -c phpstan.neon
	@./docker/cli/phpcs

create-test-db:
	./docker/cli/php bin/console --env=test doctrine:database:create --if-not-exists

migrate:
	./docker/cli/php bin/console doctrine:migrations:migrate
	./docker/cli/php bin/console --env=test doctrine:migrations:migrate

load-fixtures:
	./docker/cli/php bin/console doctrine:fixtures:load --no-interaction
	./docker/cli/php bin/console --env=test doctrine:fixtures:load --no-interaction