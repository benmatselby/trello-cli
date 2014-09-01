#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$version = '0.1';
$app = new \Cilex\Application('Trello TFS Bridge', $version);

// Configuration
$app['debug'] = true;
require __DIR__ . '/../config/config.php';

$app->command(new \Ttfs\Command\ListBoardsCommand());
$app->command(new \Ttfs\Command\CreateBoardCommand());
$app->run();
