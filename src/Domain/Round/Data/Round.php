<?php

namespace App\Domain\Round\Data;

use App\Domain\Servers\Data\Server;
use App\Utilities\HTMLFactory;
use DateTime;

class Round
{
    private HTMLFactory $purifier;

    public function __construct(
        public int $id = 0,
        public ?DateTime $initialize_datetime = null,
        public ?DateTime $start_datetime = null,
        public ?DateTime $shutdown_datetime = null,
        public ?DateTime $end_datetime = null,
        public int $server_ip = 0,
        public int $server_port = 0,
        public ?string $commit_hash = '',
        public ?string $game_mode = '',
        public ?string $game_mode_result = '',
        public ?string $end_state = '',
        public ?string $shuttle_name = '',
        public ?string $map_name = '',
        public ?string $station_name = '',
        public bool $crashed = false,
        public bool $current_round = false,
        public ?Server $server = null
    ) {
        $this->purifier = new HTMLFactory();
        if (!$this->shutdown_datetime && !$this->end_state) {
            $this->crashed = true;
        }
        if ($this->start_datetime) {
            $this->startup_duration = $this->initialize_datetime->diff($this->start_datetime)->format('%H:%I:%S');
        }
        if ($this->start_datetime && $this->end_datetime) {
            $this->duration = $this->start_datetime->diff($this->end_datetime)->format('%H:%I:%S');
            if ($this->shutdown_datetime) {
                $this->shutdown_duration = $this->end_datetime->diff($this->shutdown_datetime)->format('%H:%I:%S');
            }
        } else {
            $this->duration = 'N/A';
        }
        if ($this->station_name) {
            $this->station_name = $this->purifier->sanitizeString($this->station_name);
        }
    }

    public function setServer($server)
    {
        $this->server = $server;
        $this->setLogURLs();
    }

    public function setLogURLs()
    {
        $year = $this->initialize_datetime->format('Y');
        $month = $this->initialize_datetime->format('m');
        $day = $this->initialize_datetime->format('d');
        $path = strtolower($this->server->name) . "/data/logs/$year/$month/$day/round-" . $this->id;

        $this->logs = new \Stdclass();
        $this->logs->public = "parsed-logs/$path";
        $this->logs->admin = "raw-logs/$path";
    }

    public function isActiveRound()
    {
        $this->current_round = true;
        $this->crashed = false;
        $this->end_state = "Ongoing Round";
    }

    public static function fromDb(object $row)
    {
        return new self(
            ...get_object_vars($row)
        );
    }
}
