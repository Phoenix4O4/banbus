<?php

namespace App\Domain\Info\Service;

use App\Service\Service;
use App\Factory\SettingsFactory;
use App\Domain\Info\Repository\ServerPopulationRepository;

class ServerPopulationService extends Service
{
    private $repo;

    public function __construct(SettingsFactory $settings, ServerPopulationRepository $repo)
    {
        parent::__construct($settings);
        $this->repo = $repo;
        $this->servers = $this->settings->getSettingsByKey('servers');
    }

    public function getServerPopulations()
    {
        if (isset($_GET['json'])) {
            foreach ($this->repo->fetchServerPopulationData() as $p) {
                $servername = $this->servers[array_search($p->server_port, array_column($this->servers, 'port'))]->servername;
                $data['dates'][] = $p->date;
                $data['servers'][$servername]['players'][] = $p->playercount;
                $data['servers'][$servername]['admins'][] = $p->admincount;
                $data['servers'][$servername]['playercolor'] = substr(sha1($servername), 0, 6);
                $data['servers'][$servername]['admincolor'] = substr(sha1($servername), 6, 6);
            }
            $data['dates'] = array_unique($data['dates']);
            $this->payload->addData('pop', $data);
            return $this->payload->asJson();
        }
        $this->payload->setTemplate('infobus/serverpop.twig', true);
        return $this->payload;
    }
}
