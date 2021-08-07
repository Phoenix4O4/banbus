<?php

namespace App\Action;

use App\Action\Action;
use App\Data\Payload;

/**
 * Action.
 */
final class Changelog extends Action
{
    public function action(array $args = []): Payload
    {
        $payload = new Payload();
        $payload->addData(
            'content',
            file_get_contents(__DIR__ . "/../../changelog.md")
        );
        $payload->setTemplate('home/markdown.twig');
        $payload->addData(
            'narrow',
            true
        );

        return $payload;
    }
}
