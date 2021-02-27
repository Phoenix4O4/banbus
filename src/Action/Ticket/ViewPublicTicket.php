<?php

namespace App\Action\Ticket;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Tickets\Service\GetTickets as Ticket;
use App\Data\Payload;

class ViewPublicTicket extends Action
{
    public $template = 'tickets/mytickets.twig';
    private $ticket;

    public function __construct(Responder $responder, Ticket $ticket)
    {
        parent::__construct($responder);
        $this->ticket = $ticket;
    }

    public function action(array $args = []): Payload
    {
        $identifier = $args['identifier'];
        return $this->ticket->getPublicTicket($identifier);
    }
}
