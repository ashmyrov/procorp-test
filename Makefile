up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up symfony-init

php-cli:
	docker exec -it php-cli bash

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

symfony-init: composer-install migrations

composer-install:
	docker-compose run --rm php-cli composer install

migrations:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --no-interaction

test: test-up composer-test test-down

test-up:
	docker compose -f docker-compose.test.yml up -d

test-down:
	docker-compose -f docker-compose.test.yml down

composer-test:
	docker-compose run --rm php-cli composer test