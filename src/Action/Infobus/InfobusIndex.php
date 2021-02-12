<?php

namespace App\Action\Infobus;

use App\Action\Action;
use App\Responder\Responder;
use App\Data\Payload;

/**
 * Action.
 */
final class InfobusIndex extends Action
{
    public function __construct(Responder $responder)
    {
        parent::__construct($responder);
    }

    public function action()
    {
        $payload = new Payload();
        $payload->setTemplate('infobus/infobus.twig');
        return $payload;
    }
}
