<?php

namespace App\Action\Bans;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Bans\Service\ListBans as Bans;

class ViewPublicBans extends Action
{
    public $template = 'bans/bans.twig';
    private $bans;
    public function __construct(Responder $responder, Bans $bans)
    {
        parent::__construct($responder);
        $this->bans = $bans;
    }

    public function action()
    {
        return $this->bans->getPublicBans();
    }
}
