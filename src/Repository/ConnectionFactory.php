<?php

namespace App\Repository;

use ParagonIE\EasyDB\EasyDB;

class ConnectionFactory
{
    protected $db;
    protected $alt_db;

    public function __construct(EasyDB $db, ?EasyDB $alt_db = null)
    {
        $this->db = $db;
        $this->alt_db = $alt_db;
    }
}
