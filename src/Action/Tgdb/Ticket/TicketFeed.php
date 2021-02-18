<?php

namespace App\Action\Tgdb\Ticket;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Tickets\Service\LatestTickets as Ticket;
use App\Data\Payload;

class TicketFeed extends Action
{
    private $ticket;

    public function __construct(Responder $responder, Ticket $ticket)
    {
        parent::__construct($responder);
        $this->ticket = $ticket;
    }

    public function action(array $args = []): Payload
    {
        $id = (int) $this->request->getParsedBody()['lastId'];
        return $this->ticket->pollForNewTickets($id);
    }
}
