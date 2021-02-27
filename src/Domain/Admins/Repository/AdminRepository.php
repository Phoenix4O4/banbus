<?php

namespace App\Domain\Admins\Repository;

use App\Repository\Database;

class AdminRepository extends Database
{
    public function fetchAllAdmins(): self
    {
        $this->setResults(
            $this->db->run("SELECT a.ckey, a.rank, a.feedback, r.flags
            FROM admin a
            LEFT JOIN admin_ranks r ON a.rank = r.rank")
        );
        return $this;
    }
}
