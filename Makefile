.DEFAULT_GOAL := help
SHELL=/bin/bash
COMPOSER_ROOT=composer
TEST_DIRECTORY=tests/Application
INSTALL_DIRECTORY=install/Application
CONSOLE=cd ${TEST_DIRECTORY} && php bin/console -e test
COMPOSER=cd ${TEST_DIRECTORY} && composer
YARN=cd ${TEST_DIRECTORY} && yarn

SYLIUS_VERSION=2.0
SYMFONY_VERSION=7.2
PHP_VERSION=8.2
PLUGIN_NAME=synolia/sylius-admin-oauth-plugin

###
### DEVELOPMENT
### ¯¯¯¯¯¯¯¯¯¯¯

install: sylius ## Install Plugin on Sylius [SYLIUS_VERSION=2.0.0] [SYMFONY_VERSION=7.2] [PHP_VERSION=8.2]
.PHONY: install

reset: ## Remove dependencies
ifneq ("$(wildcard ${TEST_DIRECTORY}/bin/console)","")
	${CONSOLE} doctrine:database:drop --force --if-exists || true
endif
	rm -rf ${TEST_DIRECTORY}
.PHONY: reset

phpunit: phpunit-configure phpunit-run ## Run PHPUnit
.PHONY: phpunit

###
### OTHER
### ¯¯¯¯¯¯

sylius: sylius-standard update-dependencies install-plugin install-sylius
.PHONY: sylius

sylius-standard:
ifeq ($(shell [[ $(SYLIUS_VERSION) == *dev ]] && echo true ),true)
	${COMPOSER_ROOT} create-project sylius/sylius-standard:${SYLIUS_VERSION} ${TEST_DIRECTORY} --no-install --no-scripts
else
	${COMPOSER_ROOT} create-project sylius/sylius-standard ${TEST_DIRECTORY} "~${SYLIUS_VERSION}" --no-install --no-scripts
endif
	${COMPOSER} config allow-plugins true
ifeq ($(shell [[ $(SYLIUS_VERSION) == *dev ]] && echo true ),true)
	${COMPOSER} require sylius/sylius:"${SYLIUS_VERSION}"
else
	${COMPOSER} require sylius/sylius:"~${SYLIUS_VERSION}"
endif

update-dependencies:
	${COMPOSER} config extra.symfony.require "~${SYMFONY_VERSION}"
	${COMPOSER} update --no-progress -n

install-plugin:
	${COMPOSER} config repositories.plugin '{"type": "path", "url": "../../"}'
	${COMPOSER} config extra.symfony.allow-contrib true
	${COMPOSER} config minimum-stability "dev"
	${COMPOSER} config prefer-stable true
	${COMPOSER} req ${PLUGIN_NAME}:* --prefer-source --no-scripts
ifneq ("$(wildcard ${INSTALL_DIRECTORY})","")
	cp -r ${INSTALL_DIRECTORY} tests
endif
ifneq ("$(wildcard tests/data)","")
	cp -r tests/data/* ${TEST_DIRECTORY}/
endif

	echo "SYNOLIA_ADMIN_OAUTH_GOOGLE_CLIENT_ID=" >>  ${TEST_DIRECTORY}/.env
	echo "SYNOLIA_ADMIN_OAUTH_GOOGLE_CLIENT_SECRET=" >>  ${TEST_DIRECTORY}/.env
	echo "SYNOLIA_ADMIN_OAUTH_MICROSOFT_CLIENT_ID=" >>  ${TEST_DIRECTORY}/.env
	echo "SYNOLIA_ADMIN_OAUTH_MICROSOFT_CLIENT_SECRET=" >>  ${TEST_DIRECTORY}/.env

install-sylius:
	${CONSOLE} doctrine:database:create --if-not-exists
	${CONSOLE} doctrine:migrations:migrate -n
	${CONSOLE} sylius:fixtures:load default -n
	${YARN} install
	${YARN} build
	${CONSOLE} cache:clear

phpunit-configure:
	cp phpunit.xml.dist ${TEST_DIRECTORY}/phpunit.xml

phpunit-run:
	cd ${TEST_DIRECTORY} && ./vendor/bin/phpunit

grumphp: ## Run GrumPHP
	vendor/bin/grumphp run

help: SHELL=/bin/bash
help: ## Display this help
	@IFS=$$'\n'; for line in `grep -h -E '^[a-zA-Z_#-]+:?.*?##.*$$' $(MAKEFILE_LIST)`; do if [ "$${line:0:2}" = "##" ]; then \
	echo $$line | awk 'BEGIN {FS = "## "}; {printf "\033[33m    %s\033[0m\n", $$2}'; else \
	echo $$line | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m%s\n", $$1, $$2}'; fi; \
	done; unset IFS;
.PHONY: help
