<?php

use Slim\App;
use Slim\Views\TwigMiddleware;
use App\Middleware\UrlGeneratorMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->add(UrlGeneratorMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(TwigMiddleware::class);
};
