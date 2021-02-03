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
        GROUP_CONCAT(role SEPARATOR ', ') as `role`,
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
            $this->db->run("$this->columns WHERE ckey = ? 
        GROUP BY bantime, ckey, `server_port`
        ORDER BY bantime DESC", $ckey) as $ban
        ) {
            $bans[] = Ban::fromDb($ban);
        }
        return $bans;
    }
    public function getSingleBanByCkey($ckey, $id)
    {
        return $this->factory->buildBan($this->db->row("SELECT 
        ban.id,
        ban.round_id as `round`,
        ban.server_ip,
        ban.server_port,
        GROUP_CONCAT(r.role SEPARATOR ', ') as `role`,
        GROUP_CONCAT(r.id SEPARATOR ', ') as `banIds`,
        ban.bantime,
        ban.expiration_time as `expiration`,
        ban.reason,
        ban.ckey,
        ban.a_ckey as `admin`,
        ban.unbanned_ckey,
        ban.unbanned_datetime,
        CASE
            WHEN ban.expiration_time IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, ban.bantime, ban.expiration_time)
            ELSE 0
        END AS `minutes`,
        CASE 
            WHEN ban.expiration_time < NOW() THEN 0
            WHEN ban.unbanned_ckey IS NOT NULL THEN 0
            ELSE 1 
        END as `active`,
        round.initialize_datetime AS round_time
        FROM ban
        LEFT JOIN `round` ON round_id = round.id
        INNER JOIN ban r ON r.bantime = ban.bantime AND r.ckey = ban.ckey
        WHERE ban.ckey = ? AND ban.id = ? 
        GROUP BY ban.bantime, ban.ckey, `server_port`", $ckey, $id));
    }
}
