<?php

namespace App\Domain\Bans\Data;

class Ban
{
    public $target;
    public $admin;
    public $unbanner;
    public $server = null;
    public $roleBans = false;

    public static function fromDb(object $row)
    {
        return new self(
            ...get_object_vars($row)
        );
    }

    public function __construct(
        public int $id = 0,
        public int $round = 0,
        public string $bantime = '',
        public $expiration = false,
        public string $ckey = '',
        public ?string $c_rank = 'Player',
        public string $role = '',
        public string $a_ckey = '',
        public ?string $a_rank = 'Player',
        public int $server_ip = 0,
        public int $server_port = 0,
        public string $reason = '',
        public $unbanned_ckey = null,
        public $unbanned_datetime = null,
        public $u_rank = '',
        public int $minutes = 0,
        public int $active = 0,
        public $round_time = null,
        public $banIds = null,
    ) {
        $this->roleBans();
    }

    public function getIp()
    {
        return $this->server_ip;
    }

    public function getPort()
    {
        return $this->server_port;
    }

    public function setServer(object $server)
    {
        $this->server = $server;
    }
    private function roleBans()
    {
        if (str_contains($this->role, ', ')) {
            $this->roleBans = true;
        }
    }
}
