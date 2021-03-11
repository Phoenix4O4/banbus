<?php

namespace App\Domain\Info\Repository;

use App\Repository\Database;

class ServerPopulationRepository extends Database
{
    public function fetchServerPopulationData()
    {
        return $this->db->run("SELECT avg(playercount) as playercount, avg(admincount) as admincount,
        DATE_FORMAT(`time`, '%H:00:00') AS `date`,
        server_ip, server_port
        FROM legacy_population
        WHERE `time` BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
        GROUP BY server_port, HOUR(`time`)
        ORDER BY HOUR(`time`);");
    }
}
