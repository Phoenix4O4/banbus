<?php

namespace App\Domain\Tickets\Data;

use DateTime;

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
        public int $id = 0,
        public int $server_ip = 0,
        public int $port = 0,
        public int $round = 0,
        public int $ticket = 0,
        public ?string $action = 'Reply', //Action of an individual ticket
        public ?string $message = null,
        public ?DateTime $timestamp = null,
        public ?string $r_ckey = null,
        public ?string $s_ckey = null,
        public ?string $r_rank = 'Player',
        public ?string $s_rank = 'Player',
        public int $replies = 0,
        public ?string $status = null, //Last status for ticket listing views
        public ?DateTime $lastTimestamp = null
    ) {
        $this->addLabels();
        $this->mapAction();
        if ($this->status) {
            $this->mapStatus();
        }
        if ($this->lastTimestamp) {
            $interval = $this->timestamp->format('U') - $this->lastTimestamp->format('U');
            $this->interval = DateTime::createFromFormat('U', $interval);
        }
        $this->message = urldecode(htmlentities($this->message, ENT_QUOTES, 'UTF-8', false));
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
                $this->action_label = "Reply from";
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
                $this->action_label = "Ticket Resolved by";
                break;
            case 'Rejected':
                $this->class = 'danger';
                $this->icon = 'undo';
                $this->isAction = true;
                $this->action_label = "Ticket Rejected by";
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
                $this->action_label = "Ticket marked as IC Issue by";
                break;

        }
    }

    private function mapStatus()
    {
        switch ($this->status) {
            case 'Ticket Opened':
                $this->class = 'info';
                $this->icon = 'ticket-alt';
                $this->verb = "Ticket Opened by";
                break;

            case 'Closed':
                $this->class = 'danger';
                $this->icon = 'times-circle';
                $this->verb = "Ticket Closed by";
                break;

            case 'Resolved':
                $this->class = 'success';
                $this->icon = 'check-circle';
                $this->verb = "Ticket Resolved by";
                break;

            case 'Reply':
                $this->class = 'warning';
                $this->icon = 'reply';
                $this->verb = "Reply from";

                break;

            case 'Rejected':
                $this->class = 'danger';
                $this->icon = 'undo';
                $this->verb = "Ticket Rejected by";
                break;

            case 'IC Issue':
                $this->class = 'secondary';
                $this->icon = 'gavel';
                $this->verb = "Ticket marked as IC Issue by";
                break;

            case 'Disconnected':
                $this->status_class = "dark";
                $this->icon = "window-close";
                $this->verb = "Client Disconnected";
                break;

            case 'Reconnected':
                $this->status_class = "info";
                $this->icon = "network-wired";
                $this->verb = "Client reconnected";
                break;
        }
    }
}
