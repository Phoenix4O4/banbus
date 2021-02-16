<?php

namespace App\Action\Tgdb\Ticket;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Tickets\Service\GetTickets as Ticket;
use App\Data\Payload;

class ViewSingleTicket extends Action
{
    public $template = 'tickets/single.twig';
    private $ticket;

    public function __construct(Responder $responder, Ticket $ticket)
    {
        parent::__construct($responder);
        $this->ticket = $ticket;
    }

    public function action(array $args = []): Payload
    {
        $round = (int) $args['round'];
        $ticket = (int) $args['ticket'];
        return $this->ticket->getSingleTicket($round, $ticket);
    }
}
