<?php

namespace App\Responder;

use JsonException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use App\Data\Payload;

use function http_build_query;

/**
 * A generic responder.
 */
final class Responder
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * @var RouteParserInterface
     */
    private $routeParser;

    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * The constructor.
     *
     * @param Twig $twig The twig engine
     * @param RouteParserInterface $routeParser The route parser
     * @param ResponseFactoryInterface $responseFactory The response factory
     */
    public function __construct(
        Twig $twig,
        RouteParserInterface $routeParser,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->twig = $twig;
        $this->responseFactory = $responseFactory;
        $this->routeParser = $routeParser;
    }

    /**
     * Create a new response.
     *
     * @return ResponseInterface The response
     */
    public function createResponse(): ResponseInterface
    {
        return $this->responseFactory->createResponse()->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Output rendered template.
     *
     * @param ResponseInterface $response The response
     * @param string $template Template pathname relative to templates directory
     * @param array<mixed> $data Associative array of template variables
     *
     * @return ResponseInterface The response
     */
    public function withTemplate(
        ResponseInterface $response,
        string $template,
        array $data = [],
        $code = 200
    ): ResponseInterface {
        $response = $response->withStatus($code);
        $data['response_code'] = $code;
        return $this->twig->render($response, $template, $data);
    }

    /**
     * Creates a redirect for the given url / route name.
     *
     * This method prepares the response object to return an HTTP Redirect
     * response to the client.
     *
     * @param ResponseInterface $response The response
     * @param string $destination The redirect destination (url or route name)
     * @param array<mixed> $queryParams Optional query string parameters
     *
     * @return ResponseInterface The response
     */
    public function withRedirect(
        ResponseInterface $response,
        string $destination,
        array $queryParams = []
    ): ResponseInterface {
        if ($queryParams) {
            $destination = sprintf('%s?%s', $destination, http_build_query($queryParams));
        }

        return $response->withStatus(302)->withHeader('Location', $destination);
    }

    /**
     * Creates a redirect for the given url / route name.
     *
     * This method prepares the response object to return an HTTP Redirect
     * response to the client.
     *
     * @param ResponseInterface $response The response
     * @param string $routeName The redirect route name
     * @param array<mixed> $data Named argument replacement data
     * @param array<mixed> $queryParams Optional query string parameters
     *
     * @return ResponseInterface The response
     */
    public function withRedirectFor(
        ResponseInterface $response,
        string $routeName,
        array $data = [],
        array $queryParams = []
    ): ResponseInterface {
        return $this->withRedirect($response, $this->routeParser->urlFor($routeName, $data, $queryParams));
    }

    /**
     * Write JSON to the response body.
     *
     * This method prepares the response object to return an HTTP JSON
     * response to the client.
     *
     * @param ResponseInterface $response The response
     * @param mixed $data The data
     * @param int $options Json encoding options
     *
     * @throws JsonException
     *
     * @return ResponseInterface The response
     */
    public function withJson(
        ResponseInterface $response,
        $data = null,
        int $options = 0
    ): ResponseInterface {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($data, JSON_THROW_ON_ERROR | $options));

        return $response;
    }

    public function processPayload(ResponseInterface $response, Payload $payload)
    {
        if ($payload->checkForRedirect()) {
            return $this->withRedirect(
                $response,
                $payload->getRedirect()
            );
        }
        if ($payload->checkForRouteRedirect()) {
            return $this->withRedirectFor(
                $response,
                $payload->getRouteRedirect()
            );
        }
        if (isset($_GET['json']) || $payload->isJson()) {
            return $this->withJson($response, $payload->getData(), JSON_PRETTY_PRINT);
        }
        $template = $payload->getTemplate();
        return $this->withTemplate($response, $template, $payload->getData(), $payload->getStatusCode());
    }
}
