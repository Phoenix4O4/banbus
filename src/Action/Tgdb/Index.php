<?php

namespace App\Action\Tgdb;

use App\Action\Action;
use App\Data\Payload;

/**
 * Action.
 */
final class Index extends Action
{
    public function action(array $args = []): Payload
    {
        $payload = new Payload();
        $payload->setTemplate('tgdb/tgdb.twig');
        return $payload;
    }
}
