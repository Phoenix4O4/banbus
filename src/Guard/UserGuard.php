<?php

namespace App\Guard;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;

use App\Domain\User\Data\User;

class UserGuard
{
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        //Check for whether or not the user has the route's required permission
        $permission = $route->getArgument('permission');
        if ($permission && !$this->user) {
            die("Forbidden");
        }
        if ($permission && false === $this->user->hasPermission($permission)) {
            die("Forbidden");
        }
        return $handler->handle($request);
    }
}
