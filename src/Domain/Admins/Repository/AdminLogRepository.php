<?php

namespace App\Domain\Admins\Repository;

use ParagonIE\EasyDB\EasyDB;
use App\Repository\Database;

class AdminLogRepository extends Database
{
    public function __construct(EasyDB $db)
    {
        parent::__construct($db);
    }

    public function fetchAdminLogs(int $page = 1, int $per_page = 60): self
    {
        $this->setPages((int) ceil($this->db->cell(
            "SELECT
            count(id) 
            FROM admin_log"
        ) / $per_page));

        $this->setResults(
            $this->db->run(
                "SELECT
                  L.id,
                  L.datetime,
                  L.adminckey,
                  L.operation,
                  L.target,
                  L.log,
                  IF(A.rank IS NULL, 'Player', A.rank) as adminrank
                  FROM admin_log as L
                  LEFT JOIN admin as A ON L.adminckey = A.ckey
                  ORDER BY L.datetime DESC
                  LIMIT ?,?",
                ($page * $per_page) - $per_page,
                $per_page
            )
        );
        return $this;
    }
}
