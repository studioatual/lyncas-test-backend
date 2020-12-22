<?php

use DI\Container;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/render.php';
require_once __DIR__ . '/providers.php';
require_once __DIR__ . '/services.php';

require_once __DIR__ . '/middleware.php';
require_once __DIR__ . '/errors.php';
require_once __DIR__ . '/cors.php';

require_once __DIR__ . '/../routes/api.php';
require_once __DIR__ . '/../routes/web.php';