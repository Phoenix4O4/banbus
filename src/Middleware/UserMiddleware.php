<?php

namespace App\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use App\Domain\User\Data\User;
use Psr\Http\Server\MiddlewareInterface;

class UserMiddleware
{
    private $user;
    private $sitePermissions;

    public function __construct(?User $user = null, $sitePermissions)
    {
        $this->user = $user;
        $this->sitePermissions = $sitePermissions;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->user) {
            die("You must be authenticated to access this.");
        }
        $path = explode('/', $request->getUri()->getPath())[1];
        if (isset($this->sitePermissions[$path]) && $this->user->hasPermission($this->sitePermissions[$path])) {
            $response = $handler->handle($request);
            return $response;
        }
        die("You must be authenticated to access this.");
    }
}
