<?php

namespace App\Domain\User\Repository;

use App\Repository\Database;

final class UserRepository extends Database
{
    public function getUserByCkey($ckey)
    {
        return $this->db->row("SELECT a.rank, r.flags, a.feedback
        FROM `admin` a
        LEFT JOIN admin_ranks r ON a.rank = r.rank
        WHERE ckey = ?", $ckey);
    }

    public function getUserByDiscordId(string $discordId)
    {
        return $this->db->row("SELECT p.ckey, a.rank, r.flags, a.feedback
            FROM discord_links p
            LEFT JOIN `admin` a ON p.ckey = a.ckey
            LEFT JOIN admin_ranks r ON a.rank = r.rank
            WHERE p.discord_id = ? AND valid = 1", $discordId);
    }
}
