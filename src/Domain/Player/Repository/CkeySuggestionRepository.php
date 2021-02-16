<?php

namespace App\Domain\Player\Repository;

use App\Domain\Player\Repository\PlayerRepository;

class CkeySuggestionRepository extends PlayerRepository
{
    public function findCkeys(string $search)
    {
        return $this->db->run(
            "SELECT ckey FROM player WHERE ckey LIKE ?
              ORDER BY lastseen DESC LIMIT 0, 15",
            '%' . $this->db->escapeLikeValue($search) . '%'
        );
    }
}
