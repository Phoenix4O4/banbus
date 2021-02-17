<?php

use App\Middleware\UserMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', \App\Action\Home\Home::class)
      ->setName('home');
    $app->get('/auth', \App\Action\User\Authenticate::class)
      ->setName('auth');
    $app->get('/auth/confirm', \App\Action\User\ConfirmAuthentication::class)
      ->setName('auth_confirm');
    $app->get('/logout', \App\Action\User\Logout::class)
      ->setName('logout');

    $app->get('/banbus', \App\Action\Banbus\BanbusIndex::class)
      ->setName('banbus.index');
    $app->get('/banbus/', \App\Action\Banbus\BanbusIndex::class)
      ->setName('banbus.index');
    $app->get('/banbus/mybans', \App\Action\Bans\ViewMyBans::class)
      ->setName('mybans');
    $app->get('/banbus/mybans/{id:[0-9]+}', \App\Action\Bans\ViewSingleMyBan::class)
      ->setName('mybans.single');
    $app->get('/banbus/bans', \App\Action\Bans\ViewPublicBans::class)
      ->setName('bans');
    $app->get('/banbus/bans/{id:[0-9]+}', \App\Action\Bans\ViewSingleBan::class)
      ->setName('ban.single');
    $app->get('/banbus/mytickets[/page/{page}]', \App\Action\Ticket\ViewMyTickets::class)
      ->setName('mytickets');
    $app->get('/banbus/mytickets/{round:[0-9]+}/{ticket:[0-9]+}', \App\Action\Ticket\ViewMySingleTicket::class)
      ->setName('mytickets.single');

    $app->get('/infobus', \App\Action\Infobus\InfobusIndex::class)->setName('infobus');
    $app->get('/infobus/adminwho', \App\Action\Admins\AdminWho::class)->setName('adminwho');
    $app->get('/infobus/adminlogs[/page/{page}]', \App\Action\Admins\AdminLogs::class)->setName('adminlogs');


    $app->group('/tgdb', function (RouteCollectorProxy $app) {
        $app->get('', \App\Action\Tgdb\Index::class)
        ->setName('tgdb');

        $app->post('/ckeysearch', \App\Action\Tgdb\CkeySuggest::class)
        ->setName('ckeysuggest');

        $app->get('/player/{ckey:[a-z0-9@]+}', \App\Action\Tgdb\Player\AdminViewPlayer::class)
        ->setName('tgdb.player');
        $app->get('/player/{ckey:[a-z0-9@]+}/tickets[/page/{page}]', \App\Action\Tgdb\Player\ViewPlayerTickets::class)
        ->setName('tgdb.player.tickets');

        $app->get('/ticket/{round:[0-9]+}/{ticket:[0-9]+}', \App\Action\Tgdb\Ticket\ViewSingleTicket::class)
        ->setName('tgdb.ticket');
    })->add(UserMiddleware::class);
};
