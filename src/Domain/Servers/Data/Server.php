<?php

namespace App\Domain\Servers\Data;

class Server
{
    public function __construct(
        public string $address,
        public int $port,
        public string $servername,
        public string $name,
        public string $raw_logs,
        public string $public_logs
    ) {
    }

    public static function fromJson(object $server)
    {
        return new self(...get_object_vars($server));
    }
}
