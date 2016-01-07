#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$version = '0.1';
$app = new \Cilex\Application('Trello CLI', $version);

// Configuration
$app['debug'] = true;
require __DIR__ . '/../config/config.php';

$app->command(new \TrelloCli\Command\ListCardsCommand());
$app->command(new \TrelloCli\Command\ListBoardsCommand());
$app->command(new \TrelloCli\Command\ListPeopleCommand());
$app->command(new \TrelloCli\Command\CreateBoardCommand());
$app->command(new \TrelloCli\Command\LabelCardsCommand());
$app->command(new \TrelloCli\Command\JsonExportBoardCommand());
$app->run();
