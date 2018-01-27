explain:
	# Makefile for the Trello CLI application
	#
	#
	### Installation
	#
	# New repo from scratch?
	#  -> $$ make clean install
	#
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


.PHONY: clean
clean:
	rm -fr vendor

.PHONY: install
install:
	mkdir -p build/coverage
	mkdir -p build/logs
	composer.phar install

.PHONY: test
test:
	bin/phpunit

.PHONY: test-cov
test-cov:
	bin/phpunit --coverage-html=build/coverage/ --log-junit=build/logs/junit.xml --coverage-text

.PHONY: security-check
security-check:
	bin/security-checker security:check

.PHONY: docker-build
docker-build:
	docker build -t benmatselby/trello-cli .
