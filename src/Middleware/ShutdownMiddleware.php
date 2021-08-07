<?php

namespace App\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Responder\Responder;
use Slim\Psr7\Response;
use App\Data\Payload;

final class ShutdownMiddleware
{
    public function __construct(private Responder $responder)
    {
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (file_exists(__DIR__ . "/../../shutdown.md")) {
            $payload = new Payload();
            $payload->addData(
                'content',
                file_get_contents(__DIR__ . "/../../shutdown.md")
            );
            $payload->setTemplate('home/markdown.twig');
            $payload->addData(
                'narrow',
                true
            );
            return $this->responder->processPayload(new Response(), $payload);
        } else {
            $response = $handler->handle($request);
            return $response;
        }
    }
}
