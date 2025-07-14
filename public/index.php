<?php

declare(strict_types=1);

use WeddingSite\Router;

require_once sprintf('%s/vendor/autoload.php', dirname(__DIR__));

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router = new Router();
$router->route($uri);