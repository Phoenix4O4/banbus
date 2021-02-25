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
        $this->perm_flags = (array) $settings->getSettingsByKey('perm_flags');
    }

    public function buildUser($ckey, $rank, ?int $flags = 0, ?string $feedback = null): User
    {
        if (!in_array($rank, array_keys($this->ranks))) {
            $rank = new \stdclass();
            $rank->title = 'Player';
            $rank->backColor = '#CCC';
            $rank->foreColor = 'black';
            $rank->icon = 'user';
        } else {
            $rank = $this->ranks[$rank];
        }

        $permissions = $this->calculatePermissions($flags);

        return new User($ckey, $rank, $permissions, $flags, $feedback);
    }

    public function buildUsers($users)
    {
        foreach ($users as $u) {
            $return[] = $this->buildUser($u->ckey, $u->rank, $u->flags, $u->feedback);
        }
        return $return;
    }

    private function calculatePermissions(?int $flags = null)
    {
        if (!$flags) {
            return [];
        }
        foreach ($this->perm_flags as $p => $b) {
            if ($flags & $b) {
                $permissions[$p] = true;
            }
        }
        return $permissions;
    }
}
