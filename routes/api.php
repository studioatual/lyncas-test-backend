<?php

use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $route) {
    $route->get('/google-books', 'Api.GoogleBooksController:index')
        ->setName('api.google-books');
});