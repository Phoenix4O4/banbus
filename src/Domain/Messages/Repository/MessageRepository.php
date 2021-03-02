<?php

namespace App\Domain\Messages\Repository;

use App\Repository\Database;

class MessageRepository extends Database
{
    public function getMessagesByCkey(
        $ckey,
        $hide_secret = false,
        $page = 1,
        int $per_page = 60
    ) {
        $secret = "";
        if ($hide_secret) {
            $secret = "AND M.secret = 0";
        }
        $this->setPages((int) ceil($this->db->cell(
            "SELECT count(M.id) FROM messages M WHERE M.deleted = 0
            AND (M.expire_timestamp > NOW() OR M.expire_timestamp IS NULL)
            AND M.targetckey = ?
            $secret",
            $ckey,
        ) / $per_page));

        $this->setResults($this->db->run(
            "SELECT
              M.id,
              M.type,
              M.adminckey,
              M.targetckey,
              M.text,
              M.timestamp,
              M.round_id AS round,
              M.server_ip,
              M.server_port,
              M.lasteditor,
              M.severity,
              M.expire_timestamp AS expire,
              M.secret,
              A.rank as adminrank,
              T.rank as targetrank,
              E.rank as editorrank
              FROM messages AS M
              LEFT JOIN admin AS A ON M.adminckey = A.ckey
              LEFT JOIN admin AS T ON M.targetckey = T.ckey
              LEFT JOIN admin AS E ON M.lasteditor = E.ckey
              WHERE M.deleted = 0
              AND (M.expire_timestamp > NOW() OR M.expire_timestamp IS NULL)
              AND M.targetckey = ?
              $secret
              ORDER BY M.timestamp DESC
              LIMIT ?,?",
            $ckey,
            ($page * $per_page) - $per_page,
            $per_page
        ));
        return $this;
    }

    public function getSingleMessageById(int $id): self
    {
        $this->setResults(
            $this->db->row(
                "SELECT
                M.id,
                M.type,
                M.adminckey,
                M.targetckey,
                M.text,
                M.timestamp,
                M.round_id AS round,
                M.server_port,
                M.server_ip,
                M.lasteditor,
                M.severity,
                M.edits,
                M.expire_timestamp AS expire,
                M.secret,
                A.rank as adminrank,
                T.rank as targetrank,
                E.rank as editorrank
                FROM messages AS M
                LEFT JOIN admin AS A ON M.adminckey = A.ckey
                LEFT JOIN admin AS T ON M.targetckey = T.ckey
                LEFT JOIN admin AS E ON M.lasteditor = E.ckey
                WHERE M.id = ?
                AND M.deleted = 0
                ORDER BY M.timestamp DESC",
                $id
            )
        );
        return $this;
    }

    public function countActiveMessagesForPlayer(string $ckey)
    {
        return $this->db->run("SELECT id 
        FROM messages 
        WHERE targetckey = ? 
        AND ('expire_timestamp' < NOW() OR 'expire_timestamp' IS NULL) 
        AND deleted = 0;", $ckey);
    }
}
