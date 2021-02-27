<?php

namespace App\Repository;

use App\Repository\ConnectionFactory;

class Database extends ConnectionFactory
{
    protected $db;
    protected $alt_db;

    protected $results = null;
    protected $pages = 0;

    public function __construct(ConnectionFactory $connection)
    {
        $this->db = $connection->db;
        $this->alt_db = $connection->alt_db;
    }

    protected function setResults($results): void
    {
        $this->results = $results;
    }

    public function getResults(): array|object|null
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
