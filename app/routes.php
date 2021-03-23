<?php

use App\Middleware\UserMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get("/", \App\Action\Home\Home::class)->setName("home");
    $app->get("/changelog", \App\Action\Changelog::class)->setName("changelog");
    $app->get("/privacy", \App\Action\PrivacyPolicy::class)->setName("privacy");

    $app->get("/auth", \App\Action\User\Authenticate::class)->setName("auth");
    $app
    ->get("/auth/confirm", \App\Action\User\ConfirmAuthentication::class)
    ->setName("auth_confirm");
    $app->get("/logout", \App\Action\User\Logout::class)->setName("logout");

    $app
    ->get("/banbus", \App\Action\Banbus\BanbusIndex::class)
    ->setName("banbus.index");
    $app
    ->get("/banbus/", \App\Action\Banbus\BanbusIndex::class)
    ->setName("banbus.index");
    $app
    ->get("/banbus/mybans", \App\Action\Bans\ViewMyBans::class)
    ->setName("mybans");
    $app
    ->get("/banbus/mybans/{id:[0-9]+}", \App\Action\Bans\ViewSingleMyBan::class)
    ->setName("mybans.single");
    $app
    ->get("/banbus/bans", \App\Action\Bans\ViewPublicBans::class)
    ->setName("bans");
    $app
    ->get("/banbus/bans/{id:[0-9]+}", \App\Action\Bans\ViewSingleBan::class)
    ->setName("ban.single");
    $app
    ->get(
        "/banbus/mytickets[/page/{page}]",
        \App\Action\Ticket\ViewMyTickets::class
    )
    ->setName("mytickets");
    $app
    ->map(
        [
        'GET',
        'POST'],
        "/banbus/mytickets/{round:[0-9]+}/{ticket:[0-9]+}",
        \App\Action\Ticket\ViewMySingleTicket::class
    )
    ->add('csrf')
    ->setName("mytickets.single");

    $app
    ->get(
        "/banbus/mymessages[/page/{page}]",
        \App\Action\Messages\ViewMyMessages::class
    )
    ->setName("mymessages");

    $app
    ->get(
        "/banbus/mymessages/{id:[0-9]+}",
        \App\Action\Messages\ViewMySingleMessage::class
    )
    ->setName("mymessages.single");

    $app->get('/banbus/ticket/{identifier}', \App\Action\Ticket\ViewPublicTicket::class)->setName("publicticket");

    $app
    ->get("/infobus", \App\Action\Infobus\InfobusIndex::class)
    ->setName("infobus");
    $app
    ->get("/infobus/adminwho", \App\Action\Admins\AdminWho::class)
    ->setName("adminwho");
    $app
    ->get(
        "/infobus/adminlogs[/page/{page}]",
        \App\Action\Admins\AdminLogs::class
    )
    ->setName("adminlogs");

    $app
    ->get(
        "/infobus/serverpop",
        \App\Action\Infobus\ServerPop::class
    )
    ->setName("serverpop");


    $app->get("/ticket/{identifier}", \App\Action\Ticket\ViewPublicTicket::class);

    $app->get("/servers/", \App\Action\Servers\GetServers::class);

    $app
    ->group("/round", function (RouteCollectorProxy $app) {
        $app->get("", \App\Action\Round\Listing::class)->setName("rounds");

        $app
        ->get(
            "/{id:[0-9]+}/stat",
            \App\Action\Round\Stats\Stats::class
        )
        ->setName("round.stats");

        $app
        ->get(
            "/{id:[0-9]+}/stat/{stat:[a-zA-z_]+}",
            \App\Action\Round\Stats\GetRoundStat::class
        )
        ->setName("round.stat");
    });
    $app
    ->group("/tgdb", function (RouteCollectorProxy $app) {
        $app->get("", \App\Action\Tgdb\Index::class)->setName("tgdb");

        $app
        ->post("/ckeysearch", \App\Action\Tgdb\CkeySuggest::class)
        ->setName("ckeysuggest");

        //Player Routes
        $app
        ->get(
            "/player/{ckey:[a-z0-9@]+}",
            \App\Action\Tgdb\Player\AdminViewPlayer::class
        )
        ->setName("tgdb.player");

        $app
        ->get(
            "/player/{ckey:[a-z0-9@]+}/tickets[/page/{page}]",
            \App\Action\Tgdb\Player\ViewPlayerTickets::class
        )
        ->setName("tgdb.player.tickets");

        $app
        ->get(
            "/player/{ckey:[a-z0-9@]+}/messages[/page/{page}]",
            \App\Action\Tgdb\Player\ViewPlayerMessages::class
        )
        ->setName("tgdb.player.messages");

        $app
        ->get(
            "/player/{ckey:[a-z0-9@]+}/bans[/page/{page}]",
            \App\Action\Tgdb\Player\ViewPlayerBans::class
        )
        ->setName("tgdb.player.bans");


        //Ticket routes
        //Single Ticket
        $app
        ->get(
            "/ticket/{round:[0-9]+}/{ticket:[0-9]+}",
            \App\Action\Tgdb\Ticket\ViewSingleTicket::class
        )
        ->setName("tgdb.ticket");

        //Ticket Live Feed
        $app
        ->get("/ticket/live/", \App\Action\Tgdb\Ticket\LiveTickets::class)
        ->setName("tgdb.ticket.live");

        //Ticket Live Feed Polling
        $app
        ->post("/ticket/live/poll/", \App\Action\Tgdb\Ticket\TicketFeed::class)
        ->setName("tgdb.ticket.live.update");

        //Tickets by round
        $app
        ->get(
            "/ticket/{round:[0-9]+}/",
            \App\Action\Tgdb\Ticket\ViewTicketsForRound::class
        )
        ->setName("tgdb.ticket.round");


        //Ban routes
        $app
        ->get(
            "/ban/{id:[0-9]+}",
            \App\Action\Tgdb\Ban\TgdbViewSingleBan::class
        )
        ->setName("tgdb.ban");

        //Message routes
        $app
        ->get(
            "/message/{id:[0-9]+}",
            \App\Action\Tgdb\Message\ViewSingleMessage::class
        )
        ->setName("tgdb.message");

        //Feedback Updater
        $app
        ->map(
            ['GET','POST'],
            "/feedback",
            \App\Action\Tgdb\Feedback::class
        )
        ->add('csrf')
        ->setName("tgdb.feedback");
    })
    ->add(UserMiddleware::class);
};
