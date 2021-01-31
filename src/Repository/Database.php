<?php

namespace App\Repository;

use ParagonIE\EasyDB\EasyDB;

class Database
{
    protected $db;

    public function __construct(EasyDB $db)
    {
        $this->db = $db;
    }
}
