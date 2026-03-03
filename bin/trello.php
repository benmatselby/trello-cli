#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$client = \TrelloCli\Client::instance(\TrelloCli\Config\Adapter::getConfig());

use Symfony\Component\Console\Application;

$app = new Application('Trello CLI');
$app->addCommand(new \TrelloCli\Command\ListCardsCommand($client));
$app->addCommand(new \TrelloCli\Command\ListBoardsCommand($client));
$app->addCommand(new \TrelloCli\Command\ListPeopleCommand($client));
$app->addCommand(new \TrelloCli\Command\LabelCardsCommand($client));
$app->addCommand(new \TrelloCli\Command\JsonExportBoardCommand($client));
$app->addCommand(new \TrelloCli\Command\BurndownCommand($client));
$app->addCommand(new \TrelloCli\Command\SortCommand($client));
$app->run();
