DOCKER_COMPOSE = docker-compose
EXEC_PHP       = $(DOCKER_COMPOSE) exec -T php
EXEC_MYSQL     = $(DOCKER_COMPOSE) exec -T mysql
CONSOLE        = $(EXEC_PHP) bin/console
SYMFONY        = $(EXEC_PHP) symfony
COMPOSER       = $(EXEC_PHP) composer
ENV            = local
# Set environment
ifneq (,$(wildcard ./.env))
	ENV_VAR = .env.${ENV}
    include ${ENV_VAR}
    export
endif

##
## Symfony project
##
## To specify environment use ENV parameter for example make start ENV=test. dev by default
## make [ENV=<environment>] <command>
##
## ------

install: build db ## Setup and start the project
	$(CONSOLE) server:ca:install

start: ## start dockers
	$(DOCKER_COMPOSE) up --detach
up: start ## start dockers

build: ## Build and start dockers
	$(DOCKER_COMPOSE) up --build --remove-orphans --force-recreate --detach

stop: ## Stop dockers
	$(DOCKER_COMPOSE) stop
down: stop ## Stop dockers

kill: # Remove dockers
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

clean: kill ## Stop the project and remove dockers and vendors

reinstall: vendor-remove clean install precommit ## Just reinstall from scratch

vendor-remove: ## Install vendors
	rm -rf var vendor

vendor: composer.json composer.lock ## Install vendors
	$(COMPOSER) install
	$(EXEC_PHP) ./vendor/bin/simple-phpunit install

.PHONY: install build start up stop down kill clean reinstall vendor vendor-remove

##
## Domains
## ------

install-certs-mac: ## Setup SSL certificates for Mac
	sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain ./docker/proxy/certs/happy-job.wip.crt

add-hosts: ## Add domains into /etc/hosts
	bin/add_hosts

##
## Tools
## -------

db: vendor ## Prepare DB and run migrations
	$(EXEC_MYSQL) mysql --password=pass < database.sql
	$(CONSOLE) doctrine:migration:migrate --no-interaction

run: ## Run the csv import command. Use 'make run file=<filepath>'
	$(CONSOLE) app:csv-import $(file)

server:start: ## Run a local symfony server
	$(SYMFONY) server:start

server:stop: ## Stop a local symfony server
	$(SYMFONY) server:stop

worker-run: ## Run consumer
	$(CONSOLE) messenger:consume amqp_row_proceed --memory-limit=128MB --time-limit=3600

worker-stop: ## Stop all consumers
	$(CONSOLE) messenger:stop-workers

.PHONY: db run server worker-run worker-stop
##
## Code quality control
## ----------------------

check: phpunit psalm php-cs-fixer lint-yaml composer-validate ## Run all checks and validations

phpunit: ## Run PHPUnit test (https://phpunit.de/)
	$(EXEC_PHP) ./vendor/bin/simple-phpunit tests/ --coverage-html xHTML

lint-yaml: ## Check YAML files by Symfony YAML linter (https://symfony.com/doc/current/components/yaml.html#syntax-validation)
	$(CONSOLE) lint:yaml config --parse-tags

php-cs-fixer: ## Check PHP code style by PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --allow-risky=yes --dry-run --diff --verbose

php-cs-fixer-fix: ## Fix PHP code style by PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --allow-risky=yes --verbose

psalm: ## Run PHP code check by Psalm (https://psalm.dev/)
	$(EXEC_PHP) ./vendor/bin/psalm

lint-php: ## Check PHP files by phplint (https://github.com/overtrue/phplint)
	$(EXEC_PHP) ./vendor/bin/phplint $(FILE)

composer-validate: ## Self composer validate of composer.json and composer.lock
	$(COMPOSER) validate

.PHONY: check phpunit psalm php-cs-fixer lint-yaml composer-validate

#
# Additional
# -----------------------

help: ## This help
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##.*$$)' $(filter-out ${ENV_VAR},$(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-24s\033[0m %s\n", $$1, $$2}'| sed -e 's/\[32m## /[33m/' && printf "\n"

.PHONY: help

.DEFAULT_GOAL := help
