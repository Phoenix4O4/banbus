<?php

namespace App\Domain\Bans\Repository;

use App\Repository\Database;
use App\Domain\Bans\Data\Ban;

class BanRepository extends Database
{
    private $table = 'ban';

    private $columns = "SELECT 
        ban.id,
        round_id as `round`,
        ban.server_ip,
        ban.server_port,
        GROUP_CONCAT(role SEPARATOR ', ') as `role`,
        null as `banIds`,
        ban.bantime,
        ban.expiration_time as `expiration`,
        ban.reason,
        ban.ckey,
        c.rank as `c_rank`,
        ban.a_ckey,
        a.rank as `a_rank`,
        ban.unbanned_ckey,
        ban.unbanned_datetime,
        u.rank as `u_rank`,
        CASE
            WHEN expiration_time IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, bantime, expiration_time)
            ELSE 0
        END AS `minutes`,
        CASE 
            WHEN expiration_time < NOW() THEN 0
            WHEN unbanned_ckey IS NOT NULL THEN 0
            ELSE 1 
        END as `active`,
        round.initialize_datetime AS round_time,
        ban.edits
        FROM ban
        LEFT JOIN `round` ON round_id = round.id
        LEFT JOIN `admin` AS c ON c.ckey = ban.ckey	
        LEFT JOIN `admin` AS a ON a.ckey = ban.a_ckey
        LEFT JOIN `admin` AS u ON u.ckey = ban.unbanned_ckey";

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
            $this->db->run("$this->columns WHERE ban.ckey = ? 
        GROUP BY bantime, ckey, `server_port`
        ORDER BY bantime DESC", $ckey) as $ban
        ) {
            $bans[] = Ban::fromDb($ban); //TODO: Move to service
        }
        return $bans;
    }
    public function getSingleBanByCkey($ckey, $id)
    {
        if (
            !$ban = $this->db->row("SELECT 
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
            c.rank as `c_rank`,
            ban.a_ckey,
            a.rank as `a_rank`,
            ban.unbanned_ckey,
            ban.unbanned_datetime,
            u.rank as `u_rank`,
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
            LEFT JOIN `admin` AS c ON c.ckey = ban.ckey	
            LEFT JOIN `admin` AS a ON a.ckey = ban.a_ckey
            LEFT JOIN `admin` AS u ON u.ckey = ban.unbanned_ckey
            INNER JOIN ban r ON r.bantime = ban.bantime AND r.ckey = ban.ckey
            WHERE ban.ckey = ? AND ban.id = ? 
            GROUP BY ban.bantime, ban.ckey, `server_port`", $ckey, $id)
        ) {
            return false;
        }
        return $ban;
    }

    public function createNewAppeal(int $ban, array $appeal)
    {
        $appeal['ban'] = $ban;
        try {
            $this->alt_db->insert('appeals', $appeal);
        } catch (\Exception $e) {
            if ($this->alt_db->debug) {
                return $e->getMessage();
            } else {
                return "Your appeal could not be created";
            }
        }
        return true;
    }

    public function checkForActiveAppeal(int $ban): self
    {
        $this->setResults($this->alt_db->row("SELECT * FROM appeals WHERE ban = ?", $ban));
        return $this;
    }
}
