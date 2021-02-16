<?php

namespace App\Action\Admins;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Admins\Service\AdminInfo as Admin;
use App\Data\Payload;

class AdminWho extends Action
{
    public $template = 'admins/adminwho.twig';
    private $admin;

    public function __construct(Responder $responder, Admin $admin)
    {
        parent::__construct($responder);
        $this->admin = $admin;
    }

    public function action(array $args = []): Payload
    {
        return $this->admin->getAdminInfo();
    }
}
