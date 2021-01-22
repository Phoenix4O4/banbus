<?php

namespace App\Repository;

use Cake\Database\Connection;
use Cake\Database\Query;

class Repository {

  private $connection;

  public function __construct(Connection $connection){
      $this->connection = $connection;
  }

  public function newQuery(): Query
  {
      return $this->connection->newQuery();
  }

}