#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

\TrelloCli\Client::instance($config);

use Symfony\Component\Console\Application;

$app = new Application('Trello CLI');
$app->add(new \TrelloCli\Command\ListCardsCommand());
$app->add(new \TrelloCli\Command\ListBoardsCommand());
$app->add(new \TrelloCli\Command\ListPeopleCommand());
$app->add(new \TrelloCli\Command\CreateBoardCommand());
$app->add(new \TrelloCli\Command\LabelCardsCommand());
$app->add(new \TrelloCli\Command\JsonExportBoardCommand());
$app->run();
