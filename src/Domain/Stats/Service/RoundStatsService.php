<?php

namespace App\Domain\Stats\Service;

use App\Domain\Stats\Service\StatsService;

class RoundStatsService extends StatsService
{
    public function getStatsForRound(int $round)
    {
        $stats = $this->roundStatsRepo->findStatsForRound($round)->getResults();
        $this->payload->addData('stats', $stats);
        $this->payload->addData('round', $round);
        $this->payload->setTemplate('round/stats/stats.twig');
        return $this->payload;
    }

    public function getRoundStat(int $round, string $stat)
    {
        $stat = $this->roundStatsRepo->findStatForRound($round, $stat)->getResults();
        $stat->json = json_decode($stat->json, true)['data'];
        $this->payload->addData('stat', $stat);
        $stats = $this->roundStatsRepo->findStatsForRound($round)->getResults();
        $this->payload->addData('stats', $stats);
        $this->payload->addData('round', $round);
        $this->payload->setTemplate('round/stats/stats.twig');
        return $this->payload;
    }
}
