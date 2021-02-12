<?php

namespace App\Action\Home;

use App\Action\Action;
use App\Data\Payload;

/**
 * Action.
 */
final class Home extends Action
{
    public function action()
    {
        return new Payload();
    }
}
