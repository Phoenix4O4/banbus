<?php

namespace App\Action\User;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\User\Service\ForumAuthenticate as Auth;
use App\Data\Payload;

class Authenticate extends Action
{
    private $user;
    public function __construct(Responder $responder, Auth $user)
    {
        parent::__construct($responder);
        $this->user = $user;
    }

    public function action(array $args = []): Payload
    {
        $query = $this->request->getQueryParams();
        if (empty($query['code'])) {
            return $this->user->beginAuthentication();
        } else {
            if (empty($query['state'])) {
                die("Error: Missing state parameter");
            }
            return $this->user->confirmAuthentication($query['code'], $query['state']);
        }
    }
}
