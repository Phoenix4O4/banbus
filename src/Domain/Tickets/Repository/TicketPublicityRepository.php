<?php

namespace App\Domain\Tickets\Repository;

use App\Repository\Database;
use PDO;

class TicketPublicityRepository extends Database
{
    public function getTicketPublicityStatus(int $id): self
    {
        $this->setResults(
            $this->alt_db->row("SELECT * FROM public_tickets WHERE ticket = ?", $id)
        );

        return $this;
    }

    public function getStatusByIdent($ident): self
    {
        $this->setResults(
            $this->alt_db->row("SELECT * FROM public_tickets WHERE identifier = ?", $ident)
        );

        return $this;
    }

    public function changeStatus(int $ticket, bool $status): void
    {
        $this->alt_db->update(
            'public_tickets',
            ['status' => $status],
            ['ticket' => $ticket]
        );
    }

    public function addNewPublicTicket(int $ticket)
    {
        $this->alt_db->insert(
            'public_tickets',
            [
              'ticket' => $ticket,
              'status' => true,
              'identifier' => substr(hash('SHA512', base64_encode(random_bytes(32))), 0, 16)
            ]
        );
    }
}
