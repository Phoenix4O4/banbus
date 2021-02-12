<?php

namespace App\Action;

use App\Responder\Responder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Data\Payload;

abstract class Action
{
    public $responder;

    public $template = 'home/home.twig';

    public function __construct(Responder $responder)
    {
        $this->responder = $responder;
    }

    abstract protected function action();

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        try {
            return $this->responder->processPayload($response, $this->action($args), $this->template);
        } catch (\Exception $e) {
            $payload = new Payload();
            $payload->throwError(500, $e->getMessage());
            return $this->responder->processPayload($response, $payload, $this->template);
        }
    }
}
