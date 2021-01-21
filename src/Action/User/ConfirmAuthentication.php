<?php

namespace App\Action\User;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\User\Service\Authenticate as Auth;

class ConfirmAuthentication extends Action
{
    private $user;
    
    public function __construct(Responder $responder, Auth $user)
    {
        parent::__construct($responder);
        $this->user = $user;
    }

    public function action()
    {
        return $this->user->confirmAuthentication();
    }
}
