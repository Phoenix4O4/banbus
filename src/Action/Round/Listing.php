<?php

namespace App\Action\Round;

use App\Action\Action;
use App\Responder\Responder;
use App\Data\Payload;
use App\Domain\Round\Service\RoundListingService;

class Listing extends Action
{
    private $rounds;

    public function __construct(Responder $responder, RoundListingService $rounds)
    {
        parent::__construct($responder);
        $this->rounds = $rounds;
    }
    public function action(array $args = []): Payload
    {
        $page = ($args) ? (int) $args['page'] : 1;
        return $this->rounds->getRoundListing($page);
    }
}
