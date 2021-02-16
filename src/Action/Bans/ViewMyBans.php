<?php

namespace App\Action\Bans;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Bans\Service\ListBans as Bans;
use App\Data\Payload;

class ViewMyBans extends Action
{
    public $template = 'bans/mybans.twig';
    private $bans;
    public function __construct(Responder $responder, Bans $bans)
    {
        parent::__construct($responder);
        $this->bans = $bans;
    }

    public function action(array $args = []): Payload
    {
        return $this->bans->getBansForCurrentUser();
    }
}
