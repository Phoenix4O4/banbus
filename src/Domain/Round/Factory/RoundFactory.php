<?php

namespace App\Domain\Round\Factory;

use App\Domain\Round\Data\Round;
use App\Domain\Servers\Data\Server;
use App\Factory\SettingsFactory;

class RoundFactory
{
    private $servers;
    public $activeRounds = [];

    public function __construct(SettingsFactory $settings)
    {
        $this->servers = $settings->getSettingsByKey('servers');
    }

    public function buildRounds($rounds)
    {
        foreach ($rounds as &$round) {
            $round = $this->buildRound($round);
        }
        return $rounds;
    }

    public function buildRound($round)
    {
        $round = Round::fromDb($round);
        $round->setServer($this->mapServer($round->server_ip, $round->server_port));
        if (in_array($round->id, $this->activeRounds)) {
            $round->isActiveRound();
        }
        return $round;
    }

    private function mapServer(int $ip, int $port)
    {
        return Server::fromJson($this->servers[array_search($port, array_column($this->servers, 'port'))]);
    }

    public function setActiveRounds($rounds)
    {
        $this->activeRounds = $rounds;
    }
}
