<?php

namespace App\Domain\User\Data;

class User
{
    protected $ckey;
    public $rank;
    public $displayName;

    public function __construct($ckey, $rank)
    {
        $this->setCkey($ckey);
        $this->setRank($rank);
        $this->setDisplayName($ckey);
    }

    public function setCkey($ckey)
    {
        $this->ckey = $ckey;
    }

    public function getCkey()
    {
        return $this->ckey;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    public function setDisplayName($ckey)
    {
        $this->displayName = $ckey;
    }
}
