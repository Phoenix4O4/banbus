<?php

namespace App\Action\Library;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Library\Service\LibraryBook as Service;
use App\Data\Payload;

class SingleBook extends Action
{
    private $service;
 
    public function __construct(Responder $responder, Service $service)
    {
        parent::__construct($responder);
        $this->service = $service;
    }

    public function action(array $args = []): Payload
    {
        $moderate = false;
        if ('POST' === $this->request->getMethod()) {
            $moderate = true;
        }
        $ntbn = (int) $args['ntbn'];
        return $this->service->getBook($ntbn, $moderate);
    }
}
