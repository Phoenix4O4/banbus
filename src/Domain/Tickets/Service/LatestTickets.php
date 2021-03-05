<?php

namespace App\Domain\Tickets\Service;

use App\Service\Service;
use App\Factory\SettingsFactory;
use App\Domain\Tickets\Repository\LatestTicketRepository as Repository;
use App\Domain\Tickets\Factory\TicketFactory;

class LatestTickets extends Service
{
    private $ticketFactory;

    public function __construct(
        Repository $ticketRepo,
        SettingsFactory $settings,
        TicketFactory $ticketFactory
    ) {
        $this->ticketRepo = $ticketRepo;
        $this->ticketFactory = $ticketFactory;
        parent::__construct($settings);
    }

    public function initTicketStream()
    {
        $tickets = $this->ticketRepo->getLatestTickets()->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        $this->payload->setTemplate('tickets/live.twig');
        $this->payload->addData(
            'tickets',
            $tickets
        );
        return $this->payload;
    }
    public function pollForNewTickets(int $id)
    {
        $tickets = $this->ticketRepo->getTicketsSinceId($id)->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        $this->payload->addData(
            'tickets',
            $tickets
        );
        if (!$tickets) {
            $this->payload->addMessage("No new tickets!");
        } else {
            $this->payload->addMessage("I found some new tickets!");
        }
        return $this->payload->asJson();
    }
}
