<?php

namespace App\Action\Tgdb\Player;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Messages\Service\GetMessages;
use App\Data\Payload;

class ViewPlayerMessages extends Action
{
    private $messages;

    public function __construct(Responder $responder, GetMessages $messages)
    {
        parent::__construct($responder);
        $this->messages = $messages;
    }

    public function action(array $args = []): Payload
    {
        $ckey = filter_var(
            $args['ckey'],
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_STRIP_HIGH
        );
        $page = (isset($args['page'])) ? (int) $args['page'] : 1;

        return $this->messages->getMessagesForPlayer($ckey, $page);
    }
}
