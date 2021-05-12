<?php

namespace App\Action\Round;

use App\Action\Action;
use App\Responder\Responder;
use App\Data\Payload;

class Listing extends Action
{
    public function __construct(Responder $responder)
    {
        parent::__construct($responder);
    }
    public function action(array $args = []): Payload
    {
        $payload = new Payload();
        $payload->setTemplate('rounds/listing.twig');
        return $payload;
    }
}
