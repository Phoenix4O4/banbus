<?php

namespace App\Domain\Info\Repository;

use App\Repository\Database;

class ServerPopulationRepository extends Database
{
    public function fetchServerPopulationData()
    {
        return $this->db->run("SELECT playercount, admincount, server_ip, server_port, round_id,
            DATE_FORMAT(`time`, '%Y-%m-%d %H:00:00') AS `date`
            FROM legacy_population
            WHERE `time` BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
            GROUP BY server_port, HOUR(`time`), DAY(`time`), MONTH(`time`), YEAR(`time`)
            ORDER BY `time` DESC;");
    }
}
