<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', \App\Action\Home\HomeAction::class)
      ->setName('home');
    $app->get('/doh', \App\Action\Home\HomeError::class)
      ->setName('error');
    $app->get('/auth', \App\Action\User\Authenticate::class)
      ->setName('auth');
    $app->get('/auth/confirm', \App\Action\User\ConfirmAuthentication::class)
      ->setName('auth_confirm');
    $app->get('/logout', \App\Action\User\Logout::class)
      ->setName('logout');
};
