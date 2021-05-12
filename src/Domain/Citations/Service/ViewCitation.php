<?php

namespace App\Domain\Citations\Service;

use App\Domain\Citations\Service\CitationService;
use App\Data\Payload;

class ViewCitation extends CitationService
{
    public function viewCitation(int $id): Payload
    {
        $excludeRounds = $this->activeRounds->getActiveRounds();
        $citation = $this->repo->getCitationById($id, $excludeRounds)->getResults();
        $citation = $this->factory->buildCitation($citation);
        $this->payload->setTemplate('citations/single.twig');
        $this->payload->addData('citation', $citation);

        return $this->payload;
    }
}
