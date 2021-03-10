<?php

namespace App\Action\Infobus;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Info\Service\ServerPopulationService;
use App\Data\Payload;
use App\Factory\SettingsFactory;

class ServerPop extends Action
{
    private $service;
 
    public function __construct(Responder $responder, ServerPopulationService $service, SettingsFactory $settings)
    {
        parent::__construct($responder);
        $this->service = $service;
    }

    public function action(array $args = []): Payload
    {
        return $this->service->getServerPopulations();
    }
}
