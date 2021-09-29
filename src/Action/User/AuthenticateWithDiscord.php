<?php

namespace App\Action\User;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\User\Service\DiscordAuthenticate as Auth;
use App\Data\Payload;

class AuthenticateWithDiscord extends Action
{
    public function __construct(Responder $responder, private Auth $user)
    {
        parent::__construct($responder);
        $this->user = $user;
    }

    public function action(array $args = []): Payload
    {
        $query = $this->request->getQueryParams();
        if (empty($query['code'])) {
            $this->user->beginAuthentication();
            return $this->payload;
        } else {
            if (empty($query['state'])) {
                die("Error: Missing state parameter");
            }
            return $this->user->confirmAuthentication($query['code'], $query['state']);
        }
    }
}
