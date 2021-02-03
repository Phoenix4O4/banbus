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
        int $id,
        int $round,
        string $bantime,
        $expiration,
        string $ckey,
        string $role,
        string $admin,
        int $server_ip,
        int $server_port,
        string $reason,
        $unbanned_ckey,
        $unbanned_datetime,
        int $minutes,
        int $active,
        $round_time,
        $banIds,
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
