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

.PHONY: test
test: ## Run the unit tests
	bin/phpunit

.PHONY: test-cov
test-cov: ## Run the unit tests with code coverage
	bin/phpunit --coverage-html=build/coverage/ --log-junit=build/logs/junit.xml --coverage-text

.PHONY: security-check
security-check: ## Security checker on dependencies
	./bin/security-checker security:check

.PHONY: docker-build
docker-build: ## Build the docker container
	docker build -t benmatselby/trello-cli .
