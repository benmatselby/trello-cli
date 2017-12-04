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

clean:
	rm -fr vendor

install:
	mkdir -p build/coverage
	mkdir -p build/logs
	composer.phar install

	# Manual Steps
	# -> Now create a config file in config/config.php
	#
	# An example is below:
	cat config/config.php.dist
	#
	# You will need to generate some API keys: https://trello.com/docs/gettingstarted/index.html#getting-an-application-key

# Testing
test: test-php

test-php:
	bin/phpunit

test-cov: test-php-cov

test-php-cov:
	bin/phpunit --coverage-html=build/coverage/ --log-junit=build/logs/junit.xml

security-check:
	bin/security-checker security:check

.PHONY: clean install test test-php test-cov test-php-cov
