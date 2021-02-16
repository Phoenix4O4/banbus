<?php

namespace App\Domain\Player\Service;

use App\Data\Payload;
use App\Domain\Player\Repository\CkeySuggestionRepository;

class CkeySuggester
{
    private $CkeySuggestionRepository;

    public function __construct(
        CkeySuggestionRepository $CkeySuggestionRepository
    ) {
        $this->CkeySuggestionRepository = $CkeySuggestionRepository;
        $this->payload = new Payload();
    }

    public function suggestCkeys(string $search)
    {
        $this->payload->addData(
            'ckeys',
            $this->CkeySuggestionRepository->findCkeys($search)
        );
        return $this->payload->asJson();
    }
}
