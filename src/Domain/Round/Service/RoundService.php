<?php

namespace App\Domain\Round\Service;

use App\Service\Service;
use App\Domain\Round\Repository\RoundRepository as Repository;
use App\Factory\SettingsFactory;
use App\Provider\ActiveRounds;
use App\Domain\Round\Factory\RoundFactory;
use App\Data\Payload;

class RoundService extends Service
{
    protected $repo;
    protected $activeRounds;
    protected $factory;

    public function __construct(
        SettingsFactory $settings,
        Repository $repo,
        // Factory $factory,
        ActiveRounds $activeRounds,
        RoundFactory $factory
    ) {
        parent::__construct($settings);
        $this->repo = $repo;
        // $this->factory = $factory;
        $this->activeRounds = $activeRounds;
        $this->factory = $factory;
    }

    public function getRound(int $round): Payload
    {
        $round = $this->repo->fetchRound($round)->getResults();

        $this->payload->addData(
            'round',
            $this->factory->buildRound($round)
        );
        $this->payload->setTemplate('round/single.twig');

        return $this->payload;
    }
}
