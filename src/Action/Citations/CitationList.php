<?php

namespace App\Action\Citations;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Citations\Service\ListCitations as Service;
use App\Data\Payload;

class CitationList extends Action
{
    private $service;
 
    public function __construct(Responder $responder, Service $service)
    {
        parent::__construct($responder);
        $this->service = $service;
    }

    public function action(array $args = []): Payload
    {
        $page = ($args) ? (int) $args['page'] : 1;
        return $this->service->getCitationList($page);
    }
}
