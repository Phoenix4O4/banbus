<?php 

namespace App\Domain\User\Repository;

use App\Domain\User\Repository\UserRepository;

class UserRankInformation extends UserRepository {

  public function getUserRank($ckey){
    $query = $this->newQuery('admin');
    $query->select([
      'ckey',
      'rank'
    ]);
    $query->andWhere(['ckey'=>$ckey]);
    
    return $query->execute()->fetch();
  }

}