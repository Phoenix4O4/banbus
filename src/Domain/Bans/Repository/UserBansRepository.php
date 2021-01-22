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

    public function getUserRank($ckey)
    {
        $query = $this->queryFactory->newSelect('admin');
        $query->select([
          'ckey',
          'rank'
        ]);
        $query->where(['ckey' => $ckey]);
        $row = $query->execute()->fetch('obj');
        return $row;
    }

    public function getBansByCkey($ckey)
    {
        $query = $this->queryFactory->newSelect('ban');
        $query->select('*');
        $query->where(['ckey' => $ckey]);
        return $query->execute()->fetchAll('obj');
    }
}
