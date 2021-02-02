<?php

namespace App\Action\Home;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Bans\Service\ListBans as Bans;

/**
 * Action.
 */
final class HomeAction extends Action
{
    private $bans;
    public function __construct(Responder $responder, Bans $bans)
    {
        parent::__construct($responder);
        $this->bans = $bans;
    }

    public function action()
    {
        return $this->bans->bansHomepage();
    }
}
