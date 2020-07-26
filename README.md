# Trello CLI

Small Trello CLI application for managing a the Trello board in a SCRUM environment. It isn't intended to be a full wrapper around the Trello API, but some simple tools to automate sprint management and artefacts such as the CHANGELOG.

- It assumes you have [Scrum for Trello](http://scrumfortrello.com) installed, as it adds story points to the name.
  - If not, please prefix your card title with `(x)` where x is the number of story points.
- You can create a board and list existing boards (with all the cards on a board).

## Requirements

- [PHP version 7.4+](https://www.php.net), or [Docker](https://www.docker.com).
- API Tokens from Trello. You can get this information from [here](https://trello.com/app-key).

## Environment variables

In order to connect to Trello you require the following variables.

```bash
export TRELLO_CLI_KEY=""
export TRELLO_CLI_SECRET=""
```

## Installation via Git

```shell
git clone https://github.com/benmatselby/trello-cli.git
cd trello-cli
make clean install
bin/trello.php board:list -s
```

## Installation via Docker

```shell
docker run --rm -eTRELLO_CLI_KEY -eTRELLO_CLI_SECRET benmatselby/trello-cli board:list -s
```
