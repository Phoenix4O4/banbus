<?php

namespace App\Action\Tgdb\Ban;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Bans\Service\ListBans as Bans;
use App\Data\Payload;

class TgdbViewSingleBan extends Action
{
    private $bans;
    public function __construct(Responder $responder, Bans $bans)
    {
        parent::__construct($responder);
        $this->bans = $bans;
    }

    public function action(array $args = []): Payload
    {
        $id = $args['id'];
        return $this->bans->getSingleBan($id);
    }
}
