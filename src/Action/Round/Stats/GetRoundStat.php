<?php

namespace App\Action\Round\Stats;

use App\Action\Action;
use App\Responder\Responder;
use App\Data\Payload;
use App\Domain\Stats\Service\RoundStatsService;

class GetRoundStat extends Action
{
    private $stats;

    public function __construct(Responder $responder, RoundStatsService $stats)
    {
        parent::__construct($responder);
        $this->stats = $stats;
    }

    public function action(array $args = []): Payload
    {
        $stat = (string) $args['stat'];
        $round = (int) $args['id'];
        return $this->stats->getRoundStat($round, $stat);
    }
}
