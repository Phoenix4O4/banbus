<?php

namespace App\Domain\Admins\Repository;

use App\Repository\Database;

class FeedbackRepository extends Database
{
    public function insertFeedbackLink(string $ckey, string $link)
    {
        try {
            $this->db->update('admin', [
                'feedback' => $link
            ], [
                'ckey' => $ckey
            ]);
            $this->updateExternalActivity($ckey, $link);
            return true;
        } catch (\Eexception $e) {
            return $e->getMessage();
        }
    }

    private function updateExternalActivity(string $ckey, string $link)
    {
        $this->db->insert(
            'external_activity',
            [
                'ckey' => $ckey,
                'ip' => ip2long($_SERVER['REMOTE_ADDR']),
                'action' => 'FBL',
                'text' => "Updated feedback link to '$link'"
            ]
        );
    }
}
