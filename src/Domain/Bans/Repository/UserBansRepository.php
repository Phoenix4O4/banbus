<?php

namespace App\Domain\Bans\Repository;

use App\Repository\Database;

class UserBansRepository extends Database
{

    // public function getBansByCkey($ckey)
    // {
    //     $query = $this->queryFactory->newSelect('ban');
    //     $duration = $query->func()->timediff([
    //         'expiration_time' => 'identifier',
    //         'bantime' => 'identifier'
    //         ]);
    //     $expiration = $query->newExpr()->addCase([
    //         $query->newExpr()->add(['expiration_time' => ])
    //     ]);
    //     $query->select([
    //         'id',
    //         'round' => 'round_id',
    //         'start' => 'bantime',
    //         'expiration' => 'expiration_time',
    //         'ckey',
    //         'role',
    //         'admin' => 'a_ckey',
    //         'server_ip',
    //         'server_port',
    //         'reason',
    //         'duration' => $duration
    //     ]);
    //     $query->where(['ckey' => $ckey]);
    //     $query->orderDesc('bantime');
    //     var_dump((string) $query);
    //     return $query->execute()->fetchAll('obj');
    // }

    public function getPublicBans()
    {
        return $this->db;
    }
}
