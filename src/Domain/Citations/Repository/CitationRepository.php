<?php

namespace App\Domain\Citations\Repository;

use App\Repository\Database;

class CitationRepository extends Database
{
    public function fetchCitations(int $page = 1, int $per_page = 5, ?array $excludeRounds = null): self
    {
        if ($excludeRounds) {
            $this->generateRoundExclusionList($excludeRounds);
        }
        $this->setPages((int) ceil($this->db->cell(
            "SELECT
          count(citation.id)
          FROM citation
          WHERE citation.round_id NOT IN $this->excludedRounds"
        ) / $per_page));
        $this->setResults(
            $this->db->run(
                "SELECT 
                *
                FROM citation
                WHERE round_id NOT IN $this->excludedRounds
                ORDER BY `timestamp` DESC
                LIMIT ?, ?",
                ($page * $per_page) - $per_page,
                $per_page
            )
        );
        return $this;
    }

    public function getCitationById(int $id, ?array $excludeRounds): self
    {
        if ($excludeRounds) {
            $this->generateRoundExclusionList($excludeRounds);
        }
        $this->setResults(
            $this->db->row(
                "SELECT 
                *
                FROM citation
                WHERE round_id NOT IN $this->excludedRounds
                AND id = ?",
                $id
            )
        );
        return $this;
    }
}
