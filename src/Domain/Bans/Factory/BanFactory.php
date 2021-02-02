<?php

namespace App\Domain\Bans\Factory;

use App\Factory\SettingsFactory;
use App\Domain\Bans\Data\Ban;
use DateTime;

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
        $ban->logs = $this->mapLogLinks($ban->round, $ban->round_time, $ban->server);
        return $ban;
    }

    private function mapLogLinks(int $round, ?string $datetime, object $server)
    {
        if (!$datetime) {
            return false;
        }
        $date = new DateTime($datetime);
        var_dump($date);
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        return $server->public_logs .= "$year/$month/$day/round-$round";
    }

    private function mapServer(int $ip, int $port)
    {
        return (object) $this->servers[array_search($port, array_column($this->servers, 'port'))];
    }
}
