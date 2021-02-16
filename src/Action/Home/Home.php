<?php

namespace App\Action\Home;

use App\Action\Action;
use App\Data\Payload;

/**
 * Action.
 */
final class Home extends Action
{
    public function action(array $args = []): Payload
    {
        return new Payload();
    }
}
