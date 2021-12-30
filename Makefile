DOCKER_COMPOSE = docker-compose
EXEC_PHP       = $(DOCKER_COMPOSE) exec -T php
EXEC_MYSQL     = $(DOCKER_COMPOSE) exec -T mysql
SYMFONY        = $(EXEC_PHP) bin/console
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

install: start db ## Setup and start the project

start: ## Build and start dockers
	$(DOCKER_COMPOSE) up --build --remove-orphans --force-recreate --detach

stop: ## Stop dockers
	$(DOCKER_COMPOSE) stop

kill: # Remove dockers
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans

clean: kill ## Stop the project and remove dockers and vendors

reinstall: vendor-remove clean install ## Just reinstall from scratch

vendor-remove: ## Install vendors
	$(EXEC_PHP) rm -rf var vendor

vendor: composer.json composer.lock ## Install vendors
	$(COMPOSER) install
	$(EXEC_PHP) ./vendor/bin/simple-phpunit install

.PHONY: install start stop kill clean reinstall

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
	$(SYMFONY) doctrine:migration:migrate --no-interaction

.PHONY: db

run: ## Prepare DB and run migrations
	$(SYMFONY) app:csv-import $(file)

.PHONY: symfony

##
## Code quality control
## ----------------------

check: phpunit psalm php-cs-fixer phpmd dephpend lint-yaml composer-validate composer-require-checker ## Запустить все проверки качества кода

phpunit: ## Запустить тесты PHPUnit (https://phpunit.de/)
	$(EXEC_PHP) ./vendor/bin/simple-phpunit --coverage-html xHTML

lint-yaml: ## Проверить YAML-файлы при помощи Symfony YAML linter (https://symfony.com/doc/current/components/yaml.html#syntax-validation)
	$(SYMFONY) lint:yaml config --parse-tags

php-cs-fixer: ## Проверить PHP code style при помощи PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	$(EXEC_PHP) php-cs-fixer fix --allow-risky=yes --dry-run --diff --verbose

php-cs-fixer-fix: ## Исправить ошибки PHP code style при помощи PHP CS Fixer (https://github.com/FriendsOfPHP/PHP-CS-Fixer)
	$(EXEC_PHP) php-cs-fixer fix --allow-risky=yes --verbose

psalm: ## Запустить статический анализ PHP кода при помощи Psalm (https://psalm.dev/)
	$(EXEC_PHP) psalm

dephpend: ## Проверить код на нарушения архитектуры при помощи dePHPend (https://dephpend.com/)
	$(EXEC_PHP) bin/dephpend

phpmd: ## Проанализировать PHP код при помощи PHPMD (https://phpmd.org/)
	$(EXEC_PHP) phpmd src json phpmd.xml

composer-validate: ## Провалидировать composer.json и composer.lock при помощи встроенного в Composer валидатора
	$(COMPOSER) validate

composer-require-checker: ## Обнаружить неявные зависимости от внешних пакетов при помощи ComposerRequireChecker (https://github.com/maglnet/ComposerRequireChecker)
	$(EXEC_PHP) composer-require-checker check

.PHONY: check phpunit lint-yaml php-cs-fixer php-cs-fixer-fix psalm dephpend phpmd composer-validate composer-require-checker

#
# Вспомогательные рецепты
# -----------------------

help: ## This help
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##.*$$)' $(filter-out ${ENV_VAR},$(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-24s\033[0m %s\n", $$1, $$2}'| sed -e 's/\[32m## /[33m/' && printf "\n"

.PHONY: help

.DEFAULT_GOAL := help
