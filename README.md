Trello CLI
==========

[![Build Status](https://travis-ci.org/benmatselby/trello-cli.png?branch=master)](https://travis-ci.org/benmatselby/trello-cli)

Small Trello CLI application for managing a the Trello board in a SCRUM environment. It isn't intended to be a full wrapper around the Trello API, but some simple tools to automate sprint management and artefacts such as the CHANGELOG.

* You will need to generate an application key/secret pair and generate a config file
* It runs on php, you will need at least php 7.1 and composer or docker
* It assumes you have Scrum for Trello, as it adds story points to the name
* You can create a board and list existing boards (with all the cards on a board)

It doesn't
* Add team members to the board, you will have to remember, although I may implement this
* Add all the columns you want. Again, may add this, and have a config array of columns to create


Installation via Git
--------------------

```bash
$ git clone git@github.com:benmatselby/trello-cli.git
$ cd trello-cli
$ make clean install
$ bin/trello.php board:list -s
```

Installation via Docker
-----------------------

```bash
$ docker run --rm -eTRELLO_CLI_KEY -eTRELLO_CLI_SECRET benmatselby/trello-cli board:list -s
```

