explain:
	# Makefile for the Trello CLI application, please specify a target
	#
	#
	### Installation
	#
	# New repo from scratch?
	#  -> $$ make clean install
	#  -> Then follow the instructions the output gives you
	#
	#
	### Execution
	#
	# Run:
	#   -> $$ bin/trello.php
	#

.PHONY: clean
clean:
	rm -fr vendor

.PHONY: install
install:
	mkdir -p build/coverage
	mkdir -p build/logs
	composer.phar install
	#
	#
	### Manual Steps
	# -> Now create a config file in config/config.php
	#
	# An example is below:
	#
	cat config/config.php.dist
	#
	# Or, you can define the following environment variables
	#
	# TRELLO_CLI_KEY
	# TRELLO_CLI_SECRET
	#
	# You will need to generate some API keys: https://trello.com/docs/gettingstarted/index.html#getting-an-application-key

.PHONY: test
test:
	bin/phpunit

.PHONY: test-cov
test-cov:
	bin/phpunit --coverage-html=build/coverage/ --log-junit=build/logs/junit.xml --coverage-text

.PHONY: security-check
security-check:
	bin/security-checker security:check
