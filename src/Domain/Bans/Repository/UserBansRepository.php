<?php

namespace App\Domain\Bans\Repository;

use App\Factory\QueryFactory;

final class UserBansRepository
{
    private $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function getBansByCkey($ckey)
    {
        $query = $this->queryFactory->newSelect('ban');
        $query->select('*');
        $query->where(['ckey' => $ckey]);
        $query->orderDesc('bantime');
        return $query->execute()->fetchAll('obj');
    }
}
