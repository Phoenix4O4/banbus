<?php

namespace App\Action\Admins;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Admins\Service\AdminRankLog as Admin;
use App\Data\Payload;

class AdminLogs extends Action
{
    private $admin;

    public function __construct(Responder $responder, Admin $admin)
    {
        parent::__construct($responder);
        $this->admin = $admin;
    }

    public function action(array $args = []): Payload
    {
        $page = ($args) ? (int) $args['page'] : 1;

        return $this->admin->getAdminRankLogs($page);
    }
}
