<?php

namespace App\Domain\Tickets\Repository;

use App\Repository\Database;

class LatestTicketRepository extends Database
{
    public function getLatestTickets(): self
    {
        $this->setResults(
            $this->db->run(
                "SELECT 
                t.id,
                t.server_ip,
                t.server_port as port,
                t.round_id as round,
                t.ticket,
                t.action,
                t.message,
                t.timestamp,
                t.recipient as r_ckey,
                t.sender as s_ckey,
                r.rank as r_rank,
                s.rank as s_rank,
                null as `status`,
                0 as `replies`
                FROM ticket t
                LEFT JOIN `admin` AS r ON r.ckey = t.recipient	
                LEFT JOIN `admin` AS s ON s.ckey = t.sender
                ORDER BY `timestamp` DESC
                LIMIT 0, 10"
            )
        );
        return $this;
    }

    public function getTicketsSinceId(int $id, ?string $type = null)
    {
        $args[] = $id;
        $and = null;
        if ($type) {
            $and = "AND t.action = ?";
            $args[] = $type;
        }
        $this->setResults(
            $this->db->run(
                "SELECT 
                t.id,
                t.server_ip,
                t.server_port as port,
                t.round_id as round,
                t.ticket,
                t.action,
                t.message,
                t.timestamp,
                t.recipient as r_ckey,
                t.sender as s_ckey,
                r.rank as r_rank,
                s.rank as s_rank,
                null as `status`,
                0 as `replies`
                FROM ticket t
                LEFT JOIN `admin` AS r ON r.ckey = t.recipient	
                LEFT JOIN `admin` AS s ON s.ckey = t.sender
                WHERE t.id > ?
                $and
                ORDER BY `timestamp` DESC",
                ...$args
            )
        );
        return $this;
    }
}
