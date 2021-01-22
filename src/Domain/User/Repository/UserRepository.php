<?php

namespace App\Domain\User\Repository;

use App\Factory\QueryFactory;
use DomainException;

final class UserRepository
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
}
