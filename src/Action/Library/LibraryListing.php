<?php

namespace App\Action\Library;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Library\Service\LibraryIndex as Service;
use App\Data\Payload;

class LibraryListing extends Action
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
        return $this->service->getLibraryShelf($page);
    }
}
