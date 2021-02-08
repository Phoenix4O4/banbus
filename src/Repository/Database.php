<?php

namespace App\Repository;

use App\Factory\SettingsFactory;
use ParagonIE\EasyDB\EasyDB;

class Database
{
    protected $db;

    protected $results = null;
    protected $pages = 0;

    public function __construct(EasyDB $db)
    {
        $this->db = $db;
    }

    protected function setResults($results): void
    {
        $this->results = $results;
    }

    public function getResults(): array
    {
        return $this->results;
    }
    public function setPages(int $pages): void
    {
        $this->pages = $pages;
    }
    public function getPages(): int
    {
        return $this->pages;
    }
}
