explain:
	### Welcome
	#
	### Installation
	#
	# New repo from scratch?
	#  -> $$ make clean install
	#
	### Execution
	#
	# Locally:
	#   -> $$ export TRELLO_CLI_KEY=[your key]
	#   -> $$ export TRELLO_CLI_SECRET=[your secret]
	#   -> $$ bin/trello.php
	#
	# Docker:
	#   -> $$ export TRELLO_CLI_KEY=[your key]
	#   -> $$ export TRELLO_CLI_SECRET=[your secret]
	#   -> $$ docker run --rm -eTRELLO_CLI_KEY -eTRELLO_CLI_SECRET benmatselby/trello-cli
	#
	### Targets
	@cat Makefile* | grep -E '^[a-zA-Z_-]+:.*?## .*$$' | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


.PHONY: clean
clean: ## Clean the local dependencies
	rm -fr vendor

.PHONY: install
install: ## Install the local dependencies
	mkdir -p build/coverage
	mkdir -p build/logs
	composer install

.PHONY: composer-outdated
composer-outdated: ## Proxy composer command
	composer outdated

.PHONY: lint
lint: ## Lint the PHP files
	find src -name "*.php" -print0 | xargs -0 -n1 -P4 php -l

.PHONY: static
static: static-phpcs static-phpstan ## Run all the static analysis

.PHONY: static-phpstan
static-phpstan: ## Static analysis of the codebase
	./bin/phpstan analyse src tests

.PHONY: static-phpcs
static-phpcs: ## Static analysis phpcs
	./bin/phpcs --standard=PSR12 src tests

.PHONY: test
test: ## Run the unit tests
	bin/phpunit

.PHONY: test-cov
test-cov: ## Run the unit tests with code coverage
	XDEBUG_MODE=coverage bin/phpunit --coverage-html=build/coverage/ --log-junit=build/logs/junit.xml --coverage-text

.PHONY: docker-build
docker-build: ## Build the docker image
	docker build -t benmatselby/trello-cli .

.PHONY: docker-run
docker-run: ## Run the docker image
	docker run --rm -eTRELLO_CLI_KEY -eTRELLO_CLI_SECRET benmatselby/trello-cli board:list -s
