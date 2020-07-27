#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$client = \TrelloCli\Client::instance(\TrelloCli\Config\Adapter::getConfig());

use Symfony\Component\Console\Application;

$app = new Application('Trello CLI');
$app->add(new \TrelloCli\Command\ListCardsCommand($client));
$app->add(new \TrelloCli\Command\ListBoardsCommand($client));
$app->add(new \TrelloCli\Command\ListPeopleCommand($client));
$app->add(new \TrelloCli\Command\LabelCardsCommand($client));
$app->add(new \TrelloCli\Command\JsonExportBoardCommand($client));
$app->add(new \TrelloCli\Command\BurndownCommand($client));
$app->add(new \TrelloCli\Command\SortCommand($client));
$app->run();
