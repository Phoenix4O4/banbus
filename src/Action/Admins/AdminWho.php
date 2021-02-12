<?php

namespace App\Action\Admins;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Admins\Service\AdminInfo as Admin;

class AdminWho extends Action
{
    public $template = 'admins/adminwho.twig';
    private $admin;

    public function __construct(Responder $responder, Admin $admin)
    {
        parent::__construct($responder);
        $this->admin = $admin;
    }

    public function action(array $args = [])
    {
        return $this->admin->getAdminInfo();
    }
}
