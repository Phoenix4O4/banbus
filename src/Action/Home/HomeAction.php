<?php

namespace App\Action\Home;

use App\Action\Action;
use App\Data\Payload;

/**
 * Action.
 */
final class HomeAction extends Action
{

    public $template = 'home/home.twig';
    protected function action()
    {
        return new Payload();
    }
}
