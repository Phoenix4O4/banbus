<?php

namespace App\Domain\Servers\Data;

class Server
{
    public $gameLink;

    public function __construct(
        public string $address,
        public int $port,
        public string $servername,
        public string $name,
        public string $raw_logs,
        public string $public_logs
    ) {
        $this->gameLink = $this->gameLink();
    }

    public static function fromJson(object $server)
    {
        return new self(...get_object_vars($server));
    }

    private function gameLink()
    {
        return "byond://$this->address:$this->port";
    }
}
