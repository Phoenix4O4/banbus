<?php

namespace App\Action\Gallery;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Gallery\Service\SingleArtwork as Service;
use App\Data\Payload;

class ViewArt extends Action
{
    private $service;

    public function __construct(Responder $responder, Service $service)
    {
        parent::__construct($responder);
        $this->service = $service;
    }

    public function action(array $args = []): Payload
    {
        $cast_vote = false;
        if ('POST' === $this->request->getMethod()) {
            $cast_vote = true;
        }
        return $this->service->viewSingleArtwork($args['server'], $args['md5'], $cast_vote);
    }
}
