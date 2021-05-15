<?php

namespace App\Domain\Round\Service;

use App\Domain\Round\Service\RoundService;
use App\Data\Payload;

class RoundListingService extends RoundService
{
    public function getRoundListing(int $page = 1): Payload
    {
        if ($page === 1) {
            $this->factory->setActiveRounds($this->activeRounds->getActiveRounds());
        }
        $rounds = $this->repo->fetchRounds($page)->getResults();
        $this->payload->addData(
            'rounds',
            $this->factory->buildRounds($rounds)
        );
        $this->payload->setTemplate('rounds/listing.twig');
        $this->addPagination($page);
        return $this->payload;
    }
}
