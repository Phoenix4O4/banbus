<?php

namespace App\Domain\Citations\Service;

use App\Domain\Citations\Service\CitationService;

class ListCitations extends CitationService
{
    public function getCitationList(int $page = 1)
    {
        $excludeRounds = $this->activeRounds->getActiveRounds();
        $citations = $this->repo->fetchCitations($page, excludeRounds: $excludeRounds)->getResults();
        $citations = $this->factory->buildCitations($citations);
        $this->payload->setTemplate('citations/listing.twig');
        $this->payload->addData('citations', $citations);
        $this->addPagination($page);

        return $this->payload;
    }
}
