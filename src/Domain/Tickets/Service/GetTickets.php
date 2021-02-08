<?php

namespace App\Domain\Tickets\Service;

use App\Service\Service;
use App\Data\Payload;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Domain\Tickets\Repository\TicketRepository as Repository;
use App\Domain\Tickets\Factory\TicketFactory;
use App\Factory\SettingsFactory;
use App\Domain\User\Data\User;

class GetTickets extends Service
{
    private $currentUser;
    private $ticketFactory;

    public function __construct(
        Session $session,
        Repository $ticketRepo,
        SettingsFactory $settings,
        TicketFactory $ticketFactory
    ) {
        $this->session = $session;
        $this->ticketRepo = $ticketRepo;
        $this->ticketFactory = $ticketFactory;
        $this->payload = new Payload();
        parent::__construct($settings);
        $this->modules = $this->settings->getSettingsByKey('modules');
        $this->per_page = $this->settings->getSettingsByKey('results_per_page');
        $this->currentUser = $this->session->get('user');
    }

    public function getTicketsForCurrentUser($page)
    {
        if (!$this->currentUser) {
            $this->payload->throwError(403, "You must be logged in to access this page.");
            return $this->payload;
        }

        $ckey = $this->currentUser->getCkey();
        $tickets = $this->ticketRepo->getTicketsByCkey($ckey, $page, $this->per_page)->getResults();
        $tickets = $this->ticketRepo->getResults();
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
        $this->payload->setTemplate('tickets/mytickets.twig');
        return $this->payload;
    }

    public function getCurrentUserTicket(int $round, int $ticket)
    {
        if (!$this->currentUser) {
            $this->payload->throwError(403, "You do not have permission to view this");
        } else {
            $ckey = $this->currentUser->getCkey();
        }
        $tickets = $this->ticketRepo->getSingleTicket($round, $ticket)->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        if (!$tickets) {
            $this->payload->throwError(403, "You do not have permission to view this");
        }
        //This is kind of a hacky workaround, but we don't get a list of tickets
        //anywhere else until here really.
        //We can't select the ticket from the db by ckey because then we lose
        //ticket actions that don't have the target ckey (e.g. a close action
        //doesn't have a sender). There's probably some SQL wizardry we can
        //employ here, so this will become a TODO: Replace with SQL
        if (!in_array($ckey, $this->getTicketCkeys($tickets))) {
            $this->payload->throwError(403, "You do not have permission to view this");
        }
        $this->payload->addData(
            'tickets',
            $tickets
        );
        $this->payload->setTemplate('tickets/single.twig');
        return $this->payload;
    }

    private function getTicketCkeys($tickets)
    {
        foreach ($tickets as $t) {
            if ($t->sender instanceof User) {
                $ckeys[] = $t->sender->getCkey();
            }
            if ($t->recipient instanceof User) {
                $ckeys[] = $t->recipient->getCkey();
            }
        }
        return $ckeys;
    }
}
