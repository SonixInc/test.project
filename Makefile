up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up jobeet-init

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

jobeet-init: jobeet-composer-install jobeet-assets-install jobeet-wait-db jobeet-migrations jobeet-fixtures

jobeet-composer-install:
	docker-compose run --rm jobeet-php-cli composer install

jobeet-assets-install:
	docker-compose run --rm jobeet-node yarn install
	docker-compose run --rm jobeet-node npm rebuild node-sass

jobeet-wait-db:
	until docker-compose exec -T jobeet-mysql mysqladmin ping -h "jobeet-mysql" --silent ; do sleep 1 ; done

jobeet-migrations:
	docker-compose run --rm jobeet-php-cli php bin/console doctrine:migrations:migrate --no-interaction

jobeet-fixtures:
	docker-compose run --rm jobeet-php-cli php bin/console doctrine:fixtures:load --no-interaction
