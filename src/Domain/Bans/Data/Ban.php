<?php

namespace App\Domain\Bans\Data;

class Ban
{
    public int $id;
    public int $round;
    public string $bantime;
    public $expiration;
    public string $ckey;
    public string $role;
    public string $admin;
    public int $server_ip;
    public int $server_port;
    public string $reason;
    public $unbanned_ckey;
    public $unbanned_datetime;
    public int $minutes;
    public bool $active;
    public $round_time;
    public $banIds;

    public $server = null;
    public $roleBans = false;

    public static function fromDb(object $row)
    {
        return new self(
            ...get_object_vars($row)
        );
    }

    public function __construct(
        int $id = 0,
        int $round = 0,
        string $bantime = '',
        $expiration = false,
        string $ckey = '',
        string $role = '',
        string $admin = '',
        int $server_ip = 0,
        int $server_port = 0,
        string $reason = '',
        $unbanned_ckey = null,
        $unbanned_datetime = null,
        int $minutes = 0,
        int $active = 0,
        $round_time = null,
        $banIds = null,
        $server = null,
    ) {
        $this->id = $id;
        $this->round = $round;
        $this->bantime = $bantime;
        $this->expiration = $expiration;
        $this->ckey = $ckey;
        $this->role = $role;
        $this->admin = $admin;
        $this->server_ip = $server_ip;
        $this->server_port = $server_port;
        $this->reason = $reason;
        $this->unbanned_ckey = $unbanned_ckey;
        $this->unbanned_datetime = $unbanned_datetime;
        $this->minutes = (int) $minutes;
        $this->active = (bool) $active;
        $this->server = $server;
        $this->round_time = $round_time;
        $this->banIds = $banIds;
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
