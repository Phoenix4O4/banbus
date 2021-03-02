<?php

namespace App\Domain\Appeal\Repository;

use App\Repository\Database;
use Exception;

class AppealRepository extends Database
{
    public function addNewAppealComment(
        int $appeal,
        string $text,
        string $ckey,
        string $rank
    ) {
        try {
            $this->alt_db->insert('appeal_comment', [
                'appeal' => $appeal,
                'text' => $text,
                'ckey' => $ckey,
                'rank' => $rank,
                'type' => 'comment'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    public function getAppealById(int $id)
    {
        $appeal = $this->alt_db->row("SELECT * FROM appeals WHERE id = ?", $id);
        $appeal->comments = $this->alt_db->run("SELECT * FROM appeal_comment WHERE appeal = ?", $id);
        return $appeal;
    }
}
