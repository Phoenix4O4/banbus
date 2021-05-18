<?php

namespace App\Domain\Servers\Service;

use App\Service\Service;
use App\Factory\SettingsFactory;
use App\Domain\Servers\Data\Server;

class ServerService extends Service
{
    protected $servers;

    public function __construct(SettingsFactory $settings)
    {
        parent::__construct($settings);
        $this->servers = $settings->getSettingsByKey('servers');
        foreach ($this->servers as &$s) {
            $s = Server::fromJson($s);
        }
    }

    public function getServerByName(string $server)
    {
        return $this->servers[array_search($server, array_column($this->servers, 'name'))];
    }
}
