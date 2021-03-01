<?php

namespace App\Action\Bans;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Bans\Service\SingleUserBan as Bans;
use App\Data\Payload;

class ViewSingleMyBan extends Action
{
    public $template = 'bans/single.twig';
    private $bans;
    public function __construct(Responder $responder, Bans $bans)
    {
        parent::__construct($responder);
        $this->bans = $bans;
    }

    public function action(array $args = []): Payload
    {
        $id = (int) $args['id'];
        $appeal = null;
        if ('POST' === $this->request->getMethod()) {
            $appeal = $this->request->getParsedBody();
        }
        return $this->bans->getSingleBanForCurrentUser($id, $appeal);
    }
}
