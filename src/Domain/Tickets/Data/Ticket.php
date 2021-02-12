<?php

namespace App\Domain\Tickets\Data;

class Ticket
{
    protected $labels = [];
    public $class = "success";
    public $action_label = "Ticket Opened By";
    public $recipient = null;
    public $type = "text";
    public $icon = "times-circle";
    public $isAction = false;


    public function __construct(
        public int $server_ip = 0,
        public int $port = 0,
        public int $round = 0,
        public int $ticket = 0,
        public ?string $action = 'Reply', //Action of an individual ticket
        public ?string $message = null,
        public ?string $timestamp = null,
        public ?string $r_ckey = null,
        public ?string $s_ckey = null,
        public ?string $r_rank = 'Player',
        public ?string $s_rank = 'Player',
        public int $replies = 0,
        public ?string $status = null, //Last status for ticket listing views
        public ?string $lastTimestamp = null
    ) {
        $this->addLabels();
        $this->mapAction();
        if ($this->status) {
            $this->mapStatus();
        }
        if ($this->lastTimestamp) {
            $interval = date('U', strtotime($this->timestamp)) - date('U', strtotime($this->lastTimestamp));
            $this->interval = date('H:i:s', $interval);
        }
    }

    public static function fromDb(object $ticket)
    {
        return new self(...get_object_vars($ticket));
    }

    private function addLabels()
    {
        if ($this->s_ckey && $this->r_ckey && 'Ticket Opened' === $this->action) {
            $this->labels['isBwoink'] = true;
        }
    }

    public function isBwoink()
    {
        if (isset($this->labels['isBwoink'])) {
            return true;
        }
        return false;
    }

    private function mapAction()
    {
        switch ($this->action) {
            case 'Reply':
            default:
                $this->class = 'warning';
                $this->icon = 'reply';
                $this->action_label = "replied to";
                break;
            case 'Ticket Opened':
                $this->class = 'info';
                $this->icon = 'ticket-alt';
                $this->action_label = "Ticket Opened by";
                break;
            case 'Resolved':
                $this->class = 'success';
                $this->icon = 'check-circle';
                $this->isAction = true;
                break;
            case 'Rejected':
                $this->class = 'danger';
                $this->icon = 'undo';
                $this->isAction = true;
                break;
            case 'Disconnected':
                $this->class = 'secondary';
                $this->isAction = true;
                $this->icon = 'network-wired';
                break;
            case 'Reconnected':
                $this->class = 'secondary';
                $this->isAction = true;
                $this->icon = 'window-close';
                break;
            case 'IC Issue':
                $this->class = 'secondary';
                $this->isAction = true;
                $this->icon = 'gavel';
                break;

        }
    }

    private function mapStatus()
    {
        switch ($this->status) {
            case 'Ticket Opened':
                $this->class = 'info';
                $this->icon = 'ticket-alt';
                break;

            case 'Closed':
                $this->class = 'danger';
                $this->icon = 'times-circle';
                break;

            case 'Resolved':
                $this->class = 'success';
                $this->icon = 'check-circle';
                break;

            case 'Reply':
                $this->class = 'warning';
                $this->icon = 'reply';
                break;

            case 'Rejected':
                $this->class = 'danger';
                $this->icon = 'undo';
                break;

            case 'IC Issue':
                $this->class = 'secondary';
                $this->icon = 'gavel';
                break;

            case 'Disconnected':
                $this->status_class = "dark";
                $this->icon = "window-close";
                break;

            case 'Reconnected':
                $this->status_class = "info";
                $this->icon = "network-wired";
                break;
        }
    }
}
