<?php

namespace App\Action\Round;

use App\Action\Action;
use App\Responder\Responder;
use App\Data\Payload;
use App\Domain\Round\Service\RoundListingService;
use App\Domain\Round\Service\RoundService;

class Listing extends Action
{
    public function __construct(
        Responder $responder,
        private RoundListingService $rounds,
        private RoundService $round
    ) {
        parent::__construct($responder);
    }
    public function action(array $args = []): Payload
    {
        if (isset($_GET['round'])) {
            $payload = new Payload();
            if (str_starts_with($_GET['round'], 't')) {
                $round = filter_input(INPUT_GET, 'round', FILTER_SANITIZE_NUMBER_INT);
                $payload->setRouteRedirect('tgdb.ticket.round', ['round' => $round]);
                return $payload;
            }
            $payload->setRouteRedirect('round', ['round' => $_GET['round']]);
            return $payload;
            // return $this->round->getRound($_GET['round']);
        }
        $page = ($args) ? (int) $args['page'] : 1;
        return $this->rounds->getRoundListing($page);
    }
}
