<?php

namespace App\Domain\Stats\Repository;

use App\Domain\Stats\Repository\StatRepository;

class RoundStatsRepository extends StatRepository
{
    public function findStatsForRound(int $round): self
    {
        $this->setResults($this->db->run("SELECT key_name, key_type, id FROM feedback WHERE round_id = ? ORDER BY key_name ASC", $round));
        return $this;
    }

    public function findStatForRound(int $round, string $stat): self
    {
        $this->setResults($this->db->row("SELECT * FROM feedback WHERE round_id = ? and key_name = ? ", $round, $stat));
        return $this;
    }
}
