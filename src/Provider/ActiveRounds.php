<?php

namespace App\Provider;

use GuzzleHttp\Client as Guzzle;

class ActiveRounds
{
    private $guzzle;
    private const SERVER_INFO_JOSN = "https://tgstation13.org/dynamicimages/serverinfo.json";

    private $data;

    public function __construct(Guzzle $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    private function pollServerJson()
    {
        //TODO: Cache response
        $response = $this->guzzle->request('GET', self::SERVER_INFO_JOSN);
        $this->data = json_decode($response->getBody());
    }

    public function getActiveRounds()
    {
        $this->pollServerJson();
        $rounds = [];
        foreach ($this->data as $name => $server) {
            //TODO: Find a better way to do this
            if (is_object($server) && isset($server->round_id) && 'dmca' != $name) {
                $rounds[] = (int) $server->round_id;
            }
        }
        return $rounds;
    }
}
