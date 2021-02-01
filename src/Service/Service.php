<?php

namespace App\Service;

use App\Factory\SettingsFactory;

class Service
{

    protected $settings;

    public function __construct(SettingsFactory $settings)
    {
        $this->settings = $settings;
    }
}
