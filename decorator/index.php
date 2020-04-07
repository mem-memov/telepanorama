<?php

use Telepanorama\MailController;
use DI\Bridge\Slim\Bridge;
use DI\Container;

require_once __DIR__ . '/vendor/autoload.php';

$container = new Container();
$app = Bridge::create($container);

$app->get('/', [MailController::class, 'get']);

$app->run();
