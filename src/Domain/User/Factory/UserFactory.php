<?php

namespace App\Domain\User\Factory;

use App\Factory\SettingsFactory;
use App\Domain\User\Data\User;

class UserFactory
{
    private $ranks;

    public function __construct(SettingsFactory $settings)
    {
        $this->ranks = (array) $settings->getSettingsByKey('ranks');
        foreach ($this->ranks as $k => &$v) {
            $v->title = $k;
        }
    }

    public function buildUser($ckey, $rank)
    {
        return new User($ckey, $this->ranks[$rank]);
    }
}
