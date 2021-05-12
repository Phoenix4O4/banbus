<?php

namespace App\Domain\Citations\Service;

use App\Service\Service;
use App\Domain\Citations\Repository\CitationRepository as Repository;
use App\Factory\SettingsFactory;
use App\Domain\Citations\Factory\CitationFactory as Factory;
use App\Provider\ActiveRounds;

class CitationService extends Service
{
    protected $repo;
    protected $factory;
    protected $activeRounds;

    public function __construct(
        SettingsFactory $settings,
        Repository $repo,
        Factory $factory,
        ActiveRounds $activeRounds
    ) {
        parent::__construct($settings);
        $this->repo = $repo;
        $this->factory = $factory;
        $this->activeRounds = $activeRounds;
    }
}
