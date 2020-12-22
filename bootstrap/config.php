<?php

$container->set('settings', [
    'app' => [
        'name' => $_ENV['APP_NAME'],
        'key' => $_ENV['APP_KEY'],
        'debug' => $_ENV['APP_DEBUG'],
    ],
    'domain' => $_ENV['APP_URL']
]);