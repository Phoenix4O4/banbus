<?php

namespace App\Domain\Bans\Data;

class Ban
{
    public int $id;
    public int $round;
    public string $start;
    public string $expiration;
    public string $ckey;
    public string $role;
    public string $admin;
    public int $server_ip;
    public int $server_port;
    public string $reason;

    public function __construct()
    {
        $this->id = $id;
        $this->round = $round;
        $this->start = $start;
        $this->expiration = $expiration;
        $this->ckey = $ckey;
        $this->role = $role;
        $this->admin = $admin;
        $this->server_ip = $server_ip;
        $this->server_port = $server_port;
    }
}
