<?php

namespace App\Action\Round;

use App\Action\Action;
use App\Responder\Responder;
use App\Data\Payload;
use App\Domain\Round\Service\RoundService;

class Single extends Action
{
    public function __construct(Responder $responder, private RoundService $round)
    {
        parent::__construct($responder);
    }
    public function action(array $args = []): Payload
    {
        return $this->round->getRound((int) $args['round']);
    }
}
