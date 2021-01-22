<?php

namespace App\Action\Home;

use App\Action\Action;
use App\Data\Payload;

/**
 * Action.
 */
final class HomeAction extends Action
{
    protected function action()
    {
        return new Payload();
    }
}
