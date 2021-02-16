<?php

namespace App\Domain\Tickets\Factory;

use App\Domain\Tickets\Data\Ticket;
use App\Factory\SettingsFactory;
use App\Domain\User\Factory\UserFactory;
use App\Domain\Servers\Data\Server;

class TicketFactory
{
    private $servers;
    private $userFactory;

    private $lastTimestamp = null;

    public function __construct(SettingsFactory $settings, UserFactory $userFactory)
    {
        $this->servers = $settings->getSettingsByKey('servers');
        $this->userFactory = $userFactory;
    }

    public function buildTicket(object $ticket): Ticket
    {
        $ticket = Ticket::fromDb($ticket);
        $ticket->sender = $this->userFactory->buildUser($ticket->s_ckey, $ticket->s_rank);
        if (!$ticket->r_ckey) {
            $ticket->recipient = false;
        } else {
            $ticket->recipient = $this->userFactory->buildUser($ticket->r_ckey, $ticket->r_rank);
        }
        $ticket->server = Server::fromJson($this->mapServer($ticket->server_ip, $ticket->port));
        return $ticket;
    }
    public function buildTickets(array $tickets)
    {
        $return = [];
        foreach ($tickets as $t) {
            $t->lastTimestamp = $this->lastTimestamp;
            $return[] = $this->buildTicket($t);
            $this->lastTimestamp = $t->timestamp;
        }
        return $return;
    }

    private function mapServer(int $ip, int $port)
    {
        return (object) $this->servers[array_search($port, array_column($this->servers, 'port'))];
    }
}
