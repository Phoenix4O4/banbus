<?php

namespace App\Domain\Bans\Factory;

use App\Factory\SettingsFactory;
use App\Domain\Bans\Data\Ban;

class BanFactory
{
    private $servers;

    public function __construct(SettingsFactory $settings)
    {
        $this->servers = $settings->getSettingsByKey('servers');
    }

    public function buildBan(object $ban): Ban
    {
        $ban = Ban::fromDB($ban);
        $ban->setServer($this->mapServer($ban->getIp(), $ban->getPort()));
        return $ban;
    }

    private function mapServer(int $ip, int $port)
    {
        return (object) $this->servers[array_search($port, array_column($this->servers, 'port'))];
    }
}
