<?php

declare(strict_types=1);

use WeddingSite\Config;
use WeddingSite\Router;

require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__));

$rootDir = dirname(__DIR__);

$config = new Config($rootDir);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router = new Router($config);
$router->route($uri);