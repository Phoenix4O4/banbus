<?php

namespace App\Action\Tgdb\Player;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Bans\Service\ListBans as Bans;
use App\Data\Payload;

class ViewPlayerBans extends Action
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
        $ckey = filter_var(
            $args['ckey'],
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_STRIP_HIGH
        );
        $page = (isset($args['page'])) ? (int) $args['page'] : 1;

        return $this->bans->getBansForCkey($ckey, $page);
    }
}
