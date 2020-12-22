<?php

use Slim\Views\TwigMiddleware;

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(TwigMiddleware::create($app, $container->get('view')));