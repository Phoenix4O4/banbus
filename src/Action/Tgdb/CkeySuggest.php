<?php

namespace App\Action\Tgdb;

use App\Action\Action;
use App\Data\Payload;
use App\Domain\Player\Service\CkeySuggester;
use App\Responder\Responder;

/**
 * Action.
 */
final class CkeySuggest extends Action
{
    public function __construct(Responder $responder, CkeySuggester $CkeySuggester)
    {
        parent::__construct($responder);
        $this->CkeySuggester = $CkeySuggester;
    }
    public function action(array $args = []): Payload
    {
        $search = $this->request->getParsedBody()['ckey'];
        return $this->CkeySuggester->suggestCkeys($search);
    }
}
