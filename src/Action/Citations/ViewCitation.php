<?php

namespace App\Action\Citations;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Citations\Service\ViewCitation as Service;
use App\Data\Payload;

class ViewCitation extends Action
{
    private $service;
 
    public function __construct(Responder $responder, Service $service)
    {
        parent::__construct($responder);
        $this->service = $service;
    }

    public function action(array $args = []): Payload
    {
        $id = $args['id'];
        return $this->service->viewCitation($id);
    }
}
