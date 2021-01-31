<?php

namespace App\Domain\User\Repository;

use App\Repository\Database;

final class UserRepository extends Database
{
    public function getUserRank($ckey)
    {
        var_dump(
            $this->db
        );
        die();
        return $this->db->cell("SELECT rank FROM `admin` WHERE ckey = ?", $ckey);
    }
}
