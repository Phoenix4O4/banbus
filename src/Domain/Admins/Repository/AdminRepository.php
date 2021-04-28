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

    public function fetchAdmin(string $ckey): self
    {
        $this->setResults(
            $this->db->row("SELECT a.ckey, a.rank, a.feedback, r.flags
            FROM admin a
            LEFT JOIN admin_ranks r ON a.rank = r.rank
            WHERE a.ckey = ?", $ckey)
        );
        return $this;
    }

    public function getPlaytimeForAdmin(string $ckey)
    {
        return $this->db->run(
            "SELECT sum(delta) as `minutes`,
            job
        FROM role_time_log 
        WHERE ckey = ?
        AND job in ('Ghost','Living','Admin')
        AND `datetime` BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
        GROUP BY job",
            $ckey
        );
    }
}
