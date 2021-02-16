<?php

namespace App\Domain\Player\Repository;

use ParagonIE\EasyDB\EasyDB;
use App\Repository\Database;

class PlayerRepository extends Database
{
    public function __construct(EasyDB $db)
    {
        parent::__construct($db);
    }
}
