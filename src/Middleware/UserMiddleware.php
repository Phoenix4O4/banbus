<?php

namespace App\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Data\Payload;
use App\Responder\Responder;
use App\Domain\User\Data\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

class UserMiddleware
{
    private $user;
    private $responder;
    private $sitePermissions;
    private $session;

    public function __construct(?User $user = null, Responder $responder, Session $session, $sitePermissions)
    {
        $this->user = $user;
        $this->responder = $responder;
        $this->sitePermissions = $sitePermissions;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $arguments = $route->getArguments();
        $req = $request->getUri();
        if (!$this->user) {
            $this->session->set('destination_uri', (string) $req);
            $payload = new Payload();
            $payload->setTemplate('error/error.twig');
            $payload->throwError(403, "You must be authorized to access this page");
            return $this->responder->processPayload(new Response(), $payload);
        }
        $path = explode('/', $req->getPath())[1];
        if (isset($this->sitePermissions[$path]) && $this->user->hasPermission($this->sitePermissions[$path])) {
            $response = $handler->handle($request);
            return $response;
        }
        if (isset($arguments['permission'])) {
            if ($this->user->hasPermission($arguments['permission'])) {
                $response = $handler->handle($request);
                return $response;
            }
        }
        $this->session->set('destination_uri', (string) $req);
        $payload = new Payload();
        $payload->setTemplate('error/error.twig');
        $payload->throwError(403, "You must be authenticated to access this page");
        return $this->responder->processPayload(new Response(), $payload);
    }
}
