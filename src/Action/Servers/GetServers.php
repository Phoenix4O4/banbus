<?php

namespace App\Action\Servers;

use App\Action\Action;
use App\Responder\Responder;
use App\Data\Payload;
use App\Factory\SettingsFactory;

class GetServers extends Action
{
    private $settings;
    public function __construct(Responder $responder, SettingsFactory $settings)
    {
        parent::__construct($responder);
        $this->settings = $settings;
    }

    public function action(array $args = []): Payload
    {
        $payload = new Payload();
        $payload->addData(
            'servers',
            $this->settings->getSettingsByKey('servers')
        );
        return $payload->asJson();
    }
}
