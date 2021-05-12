<?php

namespace App\Service;

use App\Factory\SettingsFactory;
use App\Data\Payload;

class Service
{
    protected $settings;
    protected $modules = [];
    protected $per_page = [];

    public function __construct(SettingsFactory $settings)
    {
        $this->settings = $settings;
        $this->modules = $this->settings->getSettingsByKey('modules');
        $this->per_page = $this->settings->getSettingsByKey('results_per_page');
        $this->payload = new Payload();
    }

    protected function mapServer(int $ip, int $port)
    {
        return (object) $this->servers[array_search($port, array_column($this->servers, 'port'))];
    }

    protected function addPagination(int $page = 1)
    {
        $this->payload->addData(
            'pagination',
            [
                'pages' => $this->repo->getPages(),
                'currentPage' => $page
            ]
        );
    }
}
