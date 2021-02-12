<?php

namespace App\Domain\User\Repository;

use App\Repository\Database;

final class UserRepository extends Database
{
    public function getUserByCkey($ckey)
    {
        return $this->db->row("SELECT a.rank, r.flags 
        FROM `admin` a
        LEFT JOIN admin_ranks r ON a.rank = r.rank
        WHERE ckey = ?", $ckey);
    }
}
