<?php

namespace App\Action\Ticket;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Tickets\Service\GetTickets as Ticket;

class ViewMySingleTicket extends Action
{
    public $template = 'tickets/single.twig';
    private $ticket;

    public function __construct(Responder $responder, Ticket $ticket)
    {
        parent::__construct($responder);
        $this->ticket = $ticket;
    }

    public function action($args = [])
    {
        $round = (int) $args['round'];
        $ticket = (int) $args['ticket'];
        return $this->ticket->getCurrentUserTicket($round, $ticket);
    }
}
