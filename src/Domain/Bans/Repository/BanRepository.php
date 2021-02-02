<?php

namespace App\Domain\Bans\Repository;

use App\Repository\Database;
use App\Domain\Bans\Data\Ban;
use ParagonIE\EasyDB\EasyDB;
use App\Domain\Bans\Factory\BanFactory;

class BanRepository
{
    private $db;
    private $factory;

    private $table = 'ban';

    private $columns = "SELECT 
        ban.id,
        round_id as `round`,
        ban.server_ip,
        ban.server_port,
        `role`,
        bantime,
        expiration_time as `expiration`,
        reason,
        ckey,
        a_ckey as `admin`,
        unbanned_ckey,
        unbanned_datetime,
        CASE
            WHEN expiration_time IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, bantime, expiration_time)
            ELSE 0
        END AS `minutes`,
        CASE 
            WHEN expiration_time < NOW() THEN 0
            WHEN unbanned_ckey IS NOT NULL THEN 0
            ELSE 1 
        END as `active`,
        round.initialize_datetime AS round_time
        FROM ban
        LEFT JOIN `round` ON round_id = round.id";

    public function __construct(EasyDB $db, BanFactory $factory)
    {
        $this->db = $db;
        $this->factory = $factory;
    }

    public function getPublicBans()
    {
        return $this->db->run("$this->columns ORDER BY bantime DESC;");
    }

    public function getBanById(int $id)
    {
        return $this->db->row("$this->columns WHERE ban.id = ?", $id);
    }

    public function getBansByCkey($ckey)
    {
        foreach (
            $this->db->run("$this->columns WHERE ckey = ?", $ckey) as $ban
        ) {
            $bans[] = Ban::fromDb($ban);
        }
        return $bans;
    }
    public function getSingleBanByCkey($ckey, $id)
    {
        return $this->factory->buildBan($this->db->row("$this->columns WHERE ckey = ? AND ban.id = ?", $ckey, $id));
    }
}
