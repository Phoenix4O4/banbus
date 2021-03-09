<?php

namespace App\Domain\Stats\Service;

use App\Service\Service;
use App\Factory\SettingsFactory;
use App\Domain\Stats\Repository\StatRepository;
use App\Domain\Stats\Repository\RoundStatsRepository;

class StatsService extends Service
{
    protected $statRepo;
    protected $roundStatsRepo;

    public function __construct(
        SettingsFactory $settings,
        StatRepository $statRepo,
        RoundStatsRepository $roundStatsRepo
    ) {
        parent::__construct($settings);
        $this->statRepo = $statRepo;
        $this->roundStatsRepo = $roundStatsRepo;
    }
}
