<?php

namespace App\Domain\User\Repository;

use App\Repository\Database;

final class UserRepository extends Database
{
    public function getUserByCkey($ckey)
    {
        return $this->db->row("SELECT p.ckey, a.rank, r.flags, a.feedback
        FROM `player` p
        LEFT JOIN `admin` a ON a.ckey = p.ckey
        LEFT JOIN admin_ranks r ON a.rank = r.rank
        WHERE p.ckey = ?", $ckey);
    }

    public function getUserByDiscordId(string $discordId)
    {
        return $this->db->row("SELECT p.ckey, a.rank, r.flags, a.feedback
            FROM `player` p
            LEFT JOIN `admin` a ON p.ckey = a.ckey
            LEFT JOIN admin_ranks r ON a.rank = r.rank
            LEFT JOIN discord_links d ON d.ckey = p.ckey
            WHERE d.discord_id = ? AND d.valid = 1", $discordId);
    }
}
