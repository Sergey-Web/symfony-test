DOCKER_COMPOSER = docker run --rm -it -u $(shell id -u):$(shell id -g) -v ./api:/app symfony-test-php-cli sh -c

up: ## Create and start the services
	docker compose up -d

down: ## Stop the services
	docker compose down --remove-orphans

build: ## Build or rebuild the services
	docker compose build --no-cache --pull

php-cli:
	docker run --rm -it -u $(shell id -u):$(shell id -g) -v ./api:/app symfony-test-php-cli sh -c "$(COMMAND)"

composer:
	$(DOCKER_COMPOSER) "composer $(ARGS)"

composer-install:
	$(DOCKER_COMPOSER) "composer install"

xdebug-start: ## Start debug with Xdebug
	docker compose exec php-fpm sh -lc 'sed -i "s/^;zend_extension=/zend_extension=/g" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'
	docker compose restart php-fpm

xdebug-stop: ## Stop debug with Xdebug
	docker compose exec php-fpm sh -lc 'sed -i "s/^zend_extension=/;zend_extension=/g" /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini'
	docker compose restart php-fpm

php: ## Run the command in the PHP container
	docker compose exec php-fpm sh