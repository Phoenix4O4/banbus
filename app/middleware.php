<?php

use Slim\App;
use Slim\Views\TwigMiddleware;
use App\Middleware\UrlGeneratorMiddleware;
use App\Middleware\ShutdownMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->add(UrlGeneratorMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(ShutdownMiddleware::class);
    $app->add(TwigMiddleware::class);
};
