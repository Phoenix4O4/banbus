<?php

namespace App\Domain\Round\Repository;

use App\Repository\Database;
use DateTime;
use DateTimeZone;

class RoundRepository extends Database
{
    private const DATETIME_COLS = [
        'initialize_datetime',
        'start_datetime',
        'shutdown_datetime',
        'end_datetime'
    ];

    public function fetchRounds(int $page = 1, int $per_page = 60): self
    {
        $this->setPages((int) ceil($this->db->cell(
            "SELECT
          count(round.id)
          FROM round"
        ) / $per_page));

        $this->setResults($this->db->run(
            "SELECT * FROM round ORDER BY id DESC LIMIT ?,?",
            ($page * $per_page) - $per_page,
            $per_page
        ));
        foreach ($this->results as &$round) {
            foreach (self::DATETIME_COLS as $date) {
                if ($round->$date) {
                    $round->$date = new DateTime($round->$date, new DateTimeZone('UTC'));
                }
            }
        }

        return $this;
    }
    public function fetchRound(int $id): self
    {
        $this->setResults(
            $this->db->row(
                "SELECT * FROM round WHERE id = ?",
                $id
            )
        );
        $round = $this->getResults();
        foreach (self::DATETIME_COLS as $date) {
            if ($round->$date) {
                $round->$date = new DateTime($round->$date, new DateTimeZone('UTC'));
            }
        }
        $this->setResults($round);
        return $this;
    }
}
