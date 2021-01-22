<?php

namespace App\Domain\User\Service;

use App\Service\Service;
use App\Domain\User\Repository\UserRepository as Repository;

class GetCurrentUser extends Service
{

    private $repository;
    public function __construct(Repository $repository)
    {
      $this->repository = $repository;
    }

    public function getUserRank($ckey){
      return var_dump($this->repository->getUserRank($ckey));
    }
}
