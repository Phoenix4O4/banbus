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
    $app->get('/mybans', \App\Action\Bans\ViewMyBans::class)
      ->setName('mybans');
    $app->get('/mybans/{id:[0-9]+}', \App\Action\Bans\ViewSingleMyBan::class)
      ->setName('mybans.single');
    $app->get('/bans', \App\Action\Bans\ViewPublicBans::class)
      ->setName('bans');
    $app->get('/bans/{id:[0-9]+}', \App\Action\Bans\ViewSingleBan::class)
      ->setName('ban.single');
    $app->get('/mytickets[/page/{page}]', \App\Action\Ticket\ViewMyTickets::class)
      ->setName('mytickets');
    $app->get('/mytickets/{round:[0-9]+}/{ticket:[0-9]+}', \App\Action\Ticket\ViewMySingleTicket::class)
      ->setName('mytickets.single');
};
