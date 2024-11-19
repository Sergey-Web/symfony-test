DOCKER_COMPOSER = docker run --rm -it -u $(shell id -u):$(shell id -g) -v ./api:/app symfony-test-php-cli sh -c

php-cli:
	docker run --rm -it -u $(shell id -u):$(shell id -g) -v ./api:/app symfony-test-php-cli sh -c "$(COMMAND)"

composer:
	$(DOCKER_COMPOSER) "composer $(ARGS)"

composer-install:
	$(DOCKER_COMPOSER) "composer install"

