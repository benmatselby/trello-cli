explain:
	# Makefile for the Trello CLI application, please specify a target
	#
	#
	### Requirements
	#
	# PHP >= 5.4
	# Composer installed
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
	composer.phar install

	# Manual Steps
	# -> Now create a config file in config/config.php
	#
	# An example is below:
	cat config/config.php.dist
	#
	# You will need to generate some API keys: https://trello.com/docs/gettingstarted/index.html#getting-an-application-key

.PHONY: clean install
