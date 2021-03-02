<?php

namespace App\Domain\Tickets\Service;

use App\Domain\Tickets\Service\GetTickets;
use App\Data\Payload;

class TicketsForRoundService extends GetTickets
{
    public function getTicketsForRound($round, $page): Payload
    {
        $tickets = $this->ticketRepo->getTicketsForRound($round, $page)->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        $this->payload->addData(
            'tickets',
            $tickets
        );
        $this->payload->addData(
            'pagination',
            [
                'pages' => $this->ticketRepo->getPages(),
                'currentPage' => $page
            ]
        );
        $this->payload->addData('round', $round);
        $this->payload->setTemplate('tickets/round_tickets.twig');
        return $this->payload;
    }
}
