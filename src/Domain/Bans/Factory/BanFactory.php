<?php

namespace App\Domain\Bans\Factory;

use App\Factory\SettingsFactory;
use App\Domain\Bans\Data\Ban;
use App\Domain\Servers\Data\Server;
use App\Domain\User\Factory\UserFactory;
use DateTime;

class BanFactory
{
    private $servers;
    private $userFactory;

    public function __construct(SettingsFactory $settings, UserFactory $userFactory)
    {
        $this->servers = $settings->getSettingsByKey('servers');
        $this->userFactory = $userFactory;
    }

    public function buildBan(object $ban, ?object $appeal = null): Ban
    {
        $ban = Ban::fromDB($ban);
        $ban->setServer($this->mapServer($ban->getIp(), $ban->getPort()));
        $ban->logs = $this->mapLogLinks($ban->round, $ban->round_time, $ban->server);
        $ban->target = $this->userFactory->buildUser($ban->ckey, $ban->c_rank);
        $ban->admin = $this->userFactory->buildUser($ban->a_ckey, $ban->a_rank);
        $ban->unbanner = $this->userFactory->buildUser($ban->unbanned_ckey, $ban->u_rank);
        $ban->appeal = $appeal;

        return $ban;
    }

    private function mapLogLinks(int $round, ?string $datetime, object $server)
    {
        if (!$datetime) {
            return false;
        }
        $date = new DateTime($datetime);
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        return $server->public_logs .= "$year/$month/$day/round-$round";
    }

    private function mapServer(int $ip, int $port)
    {
        return Server::fromJson($this->servers[array_search($port, array_column($this->servers, 'port'))]);
    }
}
