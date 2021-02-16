<?php

namespace App\Domain\Player\Repository;

use App\Domain\Player\Repository\PlayerRepository;

class ViewPlayerRepository extends PlayerRepository
{
    public function getPlayerByCkey(string $ckey)
    {
        return $this->db->row(
        "SELECT p.*,
        a.rank,
        r.flags AS perms
        FROM player p
        LEFT JOIN `admin` a ON p.ckey = a.ckey
        LEFT JOIN admin_ranks r ON a.rank = r.rank
        WHERE p.ckey = ?",
        $ckey
    );
    }
}
