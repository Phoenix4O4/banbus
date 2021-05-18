<?php

namespace App\Domain\Gallery\Service;

use App\Service\Service;
use App\Factory\SettingsFactory;

class GetServers extends Service
{
    public function __construct(SettingsFactory $settings)
    {
        parent::__construct($settings);
    }

    public function getServerList()
    {
        $this->payload->addData('servers', $this->settings->getSettingsByKey('servers'));
        $this->payload->setTemplate('gallery/servers.twig');
        return $this->payload;
    }
}
