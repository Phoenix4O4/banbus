<?php

namespace App\Domain\Bans\Repository;

use App\Repository\Database;

class BanRepository extends Database
{

    private $table = 'ban';

    private $columns = "SELECT 
            id,
            round_id as `round`,
            server_ip,
            server_port,
            `role`,
            bantime,
            expiration_time as `expiration`,
            reason,
            ckey,
            a_ckey as `admin`,
            unbanned_ckey,
            unbanned_datetime,
            TIMESTAMPDIFF(MINUTE, bantime, expiration_time) AS `minutes`,
            CASE 
                WHEN expiration_time < NOW() THEN 0
                WHEN unbanned_ckey IS NOT NULL THEN 0
                ELSE 1 
            END as `active`";

    public function getPublicBans()
    {
        return $this->db->run("$this->columns
        FROM $this->table ORDER BY bantime DESC;");
    }

    public function getBanById(int $id)
    {
        return $this->db->row("$this->columns
        FROM $this->table WHERE id = ?", $id);
    }

    public function getBansByCkey($ckey)
    {
        return $this->db->run("$this->columns
        FROM $this->table WHERE ckey = ?", $ckey);
    }

    public function getSingleBanByCkey($ckey, $id)
    {
        return $this->db->row("$this->columns
        FROM $this->table WHERE ckey = ? AND id = ?", $ckey, $id);
    }
}