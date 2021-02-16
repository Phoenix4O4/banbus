<?php

namespace App\Action\Tgdb\Player;

use App\Action\Action;
use App\Data\Payload;
use App\Domain\Tickets\Service\GetTickets;
use App\Responder\Responder;

final class ViewPlayerTickets extends Action
{
    private $GetTickets;

    public function __construct(Responder $responder, GetTickets $GetTickets)
    {
        parent::__construct($responder);
        $this->GetTickets = $GetTickets;
    }

    public function action(array $args = []): Payload
    {
        $ckey = filter_var(
            $args['ckey'],
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_STRIP_HIGH
        );
        $page = (isset($args['page'])) ? (int) $args['page'] : 1;
        return $this->GetTickets->getTicketsForCkey($ckey, $page);
    }
}
