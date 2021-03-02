<?php

namespace App\Action\Tgdb\Ticket;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Tickets\Service\TicketsForRoundService as Ticket;
use App\Data\Payload;

class ViewTicketsForRound extends Action
{
    public $template = 'tickets/round.twig';
    private $ticket;

    public function __construct(Responder $responder, Ticket $ticket)
    {
        parent::__construct($responder);
        $this->ticket = $ticket;
    }

    public function action(array $args = []): Payload
    {
        $round = filter_var(
            $args['round'],
            FILTER_VALIDATE_INT
        );
        $page = (isset($args['page'])) ? (int) $args['page'] : 1;
        return $this->ticket->getTicketsForRound($round, $page);
    }
}
