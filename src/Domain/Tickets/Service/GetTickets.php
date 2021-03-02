<?php

namespace App\Domain\Tickets\Service;

use App\Service\Service;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Domain\Tickets\Repository\TicketRepository as Repository;
use App\Domain\Tickets\Repository\TicketPublicityRepository as PublicRepo;
use App\Domain\Tickets\Factory\TicketFactory;
use App\Factory\SettingsFactory;
use App\Domain\User\Data\User;

class GetTickets extends Service
{
    protected $currentUser;
    protected $ticketFactory;

    public function __construct(
        Session $session,
        Repository $ticketRepo,
        SettingsFactory $settings,
        TicketFactory $ticketFactory,
        PublicRepo $publicRepo
    ) {
        parent::__construct($settings);
        $this->session = $session;
        $this->ticketRepo = $ticketRepo;
        $this->ticketFactory = $ticketFactory;
        $this->currentUser = $this->session->get('user');
        $this->publicRepo = $publicRepo;
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

    public function getCurrentUserTicket(int $round, int $ticket, bool $toggle = false)
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
        $canPublicize = false;
        if (!$tickets[0]->recipient && $ckey === $tickets[0]->sender->getCkey()) {
            $canPublicize = true;
        }
        if ($tickets[0]->recipient && $ckey === $tickets[0]->recipient->getCkey()) {
            $canPublicize = true;
        }
        if ($canPublicize && $toggle) {
            $this->togglePublicity($tickets[0]->id);
        }
        $publicityStatus = $this->publicRepo->getTicketPublicityStatus($tickets[0]->id)->getResults();
        $this->payload->addData(
            'publicityStatus',
            $publicityStatus
        );
        $this->payload->addData(
            'canPublicize',
            $canPublicize
        );
        $this->payload->addData(
            'tickets',
            $tickets
        );
        if ($toggle) {
            if ($publicityStatus->status) {
                $this->payload->addSuccessMessage("This ticket has been marked as public.");
            } else {
                $this->payload->addSuccessMessage("This ticket has been marked as private.");
            }
        }
        $this->payload->setTemplate('tickets/single.twig');
        return $this->payload;
    }

    public function getTicketsForCkey(string $ckey, int $page = 1)
    {
        $tickets = $this->ticketRepo->getTicketsByCkey($ckey, $page, $this->per_page)->getResults();
        $tickets = $this->ticketRepo->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        $this->payload->addData(
            'tickets',
            $tickets
        );
        $this->payload->addData(
            'ckey',
            $ckey
        );
        $this->payload->addData(
            'pagination',
            [
                'pages' => $this->ticketRepo->getPages(),
                'currentPage' => $page
            ]
        );
        $this->payload->setTemplate('tickets/playertickets.twig');
        return $this->payload;
    }

    public function getSingleTicket(int $round, int $ticket)
    {
        $tickets = $this->ticketRepo->getSingleTicket($round, $ticket)->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        if (!$tickets) {
            $this->payload->throwError(404, "This ticket could not be located");
        }
        $this->payload->addData('tickets', $tickets);
        $this->payload->setTemplate('tickets/single.twig');
        return $this->payload;
    }

    public function getPublicTicket($identifier)
    {
        $status = $this->publicRepo->getStatusByIdent($identifier)->getResults();
        if (!$status || !$status->status) {
            $this->payload->throwError(404, "This ticket could not be located");
        }
        $tickets = $this->ticketRepo->getTicketfromId($status->ticket)->getResults();
        $tickets = $this->ticketFactory->buildTickets($tickets);
        $this->payload->addData('tickets', $tickets);
        $this->payload->addData('publicityStatus', $status);
        $this->payload->addData('canPublicize', false);
        //TODO: For some reason, canPublicize is working out to true in the
        //template. This needs to be tracked down and fixed

        $this->payload->setTemplate('tickets/publicticket.twig');
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

    private function togglePublicity(int $ticket)
    {
        $status = $this->publicRepo->getTicketPublicityStatus($ticket)->getResults();
        if ($status) {
            $newStatus = !$status->status;
            $this->publicRepo->changeStatus($ticket, $newStatus);
        } else {
            $this->publicRepo->addNewPublicTicket($ticket);
        }
    }
}
