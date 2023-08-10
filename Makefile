CONTEXT := -f ./docker/docker-compose.yml --env-file .env

.PHONY: start stop up upd down rebuild install ssh artisan

start:
	docker-compose $(CONTEXT) start

stop:
	docker-compose $(CONTEXT) stop

up:
	docker-compose $(CONTEXT) up

upd:
	docker-compose $(CONTEXT) up -d

down:
	docker-compose $(CONTEXT) down --remove-orphans

rebuild:
	@make down
	docker-compose $(CONTEXT) build

install:
	docker-compose $(CONTEXT) exec php-cli composer install --no-scripts && \
	docker-compose $(CONTEXT) exec php-cli composer run-script post-autoload-dump && \
	docker-compose $(CONTEXT) exec php-cli composer run-script post-update-cmd && \
	docker-compose $(CONTEXT) exec php-cli composer run-script post-root-package-install && \
	docker-compose $(CONTEXT) exec php-cli composer run-script post-create-project-cmd && \
	docker-compose $(CONTEXT) exec php-cli php ./docker/mysql/init_testing_database.php

ssh:
	docker-compose $(CONTEXT) exec --user appuser php-cli zsh

ssh-root:
	docker-compose $(CONTEXT) exec --user root php-cli zsh
