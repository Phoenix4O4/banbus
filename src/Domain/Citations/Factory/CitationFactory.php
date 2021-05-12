<?php

namespace App\Domain\Citations\Factory;

use App\Factory\SettingsFactory;
use App\Domain\Servers\Data\Server;
use App\Utilities\HTMLFactory;

class CitationFactory
{
    private $servers;
    private $purifier;

    public function __construct(SettingsFactory $settings, HTMLFactory $purifier)
    {
        $this->servers = $settings->getSettingsByKey('servers');
        $this->purifier = $purifier;
    }

    public function buildCitation(object $citation)
    {
        $citation->server = Server::fromJson($this->mapServer($citation->server_ip, $citation->server_port));
        $citation->crime = $this->purifier->sanitizeString($citation->crime);
        $citation->unpaid = true;
        if ($citation->paid >= $citation->fine) {
            $citation->unpaid = false;
        }
        $citation->citation = (int) $citation->citation;
        return $citation;
    }

    public function buildCitations($citations)
    {
        foreach ($citations as &$c) {
            $c = $this->buildCitation($c);
        }
        return $citations;
    }

    private function mapServer(int $ip, int $port)
    {
        return (object) $this->servers[array_search($port, array_column($this->servers, 'port'))];
    }
}
