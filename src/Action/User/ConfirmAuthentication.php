<?php

namespace App\Action\User;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\User\Service\Authenticate as Auth;
use App\Data\Payload;

class ConfirmAuthentication extends Action
{
    private $auth;

    public function __construct(Responder $responder, Auth $auth)
    {
        parent::__construct($responder);
        $this->auth = $auth;
    }

    public function action(array $args = []): Payload
    {
        return $this->auth->confirmAuthentication();
    }
}
