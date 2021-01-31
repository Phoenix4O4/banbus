<?php

namespace App\Action\Bans;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Bans\Service\ListBans as Bans;

class ViewSingleMyBan extends Action
{
    public $template = 'bans/single.twig';
    private $bans;
    public function __construct(Responder $responder, Bans $bans)
    {
        parent::__construct($responder);
        $this->bans = $bans;
    }

    public function action($args = [])
    {
        $id = (int) $args['id'];
        return $this->bans->getSingleBanForCurrentUser($id);
    }
}
