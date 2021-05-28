<?php

use Slim\App;
use Slim\Views\TwigMiddleware;
use App\Middleware\UrlGeneratorMiddleware;
use Selective\SameSiteCookie\SameSiteCookieMiddleware;
use Selective\SameSiteCookie\SameSiteCookieConfiguration;

return function (App $app) {
    $configuration = new SameSiteCookieConfiguration(['same_site' => 'strict']);

    $app->add(new SameSiteCookieMiddleware($configuration));
    $app->addBodyParsingMiddleware();
    $app->add(UrlGeneratorMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(TwigMiddleware::class);
};
