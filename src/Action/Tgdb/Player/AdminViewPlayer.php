<?php

namespace App\Action\Tgdb\Player;

use App\Action\Action;
use App\Data\Payload;
use App\Domain\Player\Service\ViewPlayer;
use App\Responder\Responder;

final class AdminViewPlayer extends Action
{
    private $viewPlayer;

    public function __construct(Responder $responder, ViewPlayer $viewPlayer)
    {
        parent::__construct($responder);
        $this->viewPlayer = $viewPlayer;
    }

    public function action(array $args = []): Payload
    {
        $ckey = filter_var(
            $args['ckey'],
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_STRIP_HIGH
        );
        return $this->viewPlayer->getPlayerInformation($ckey);
    }
}
