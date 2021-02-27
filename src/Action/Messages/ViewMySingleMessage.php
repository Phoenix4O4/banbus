<?php

namespace App\Action\Messages;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Messages\Service\GetMessages;
use App\Data\Payload;

class ViewMySingleMessage extends Action
{
    private $messages;

    public function __construct(Responder $responder, GetMessages $messages)
    {
        parent::__construct($responder);
        $this->messages = $messages;
    }

    public function action(array $args = []): Payload
    {
        $id = $args['id'];
        return $this->messages->getSingleMessageForCurrentUser($id);
    }
}
