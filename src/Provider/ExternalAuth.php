<?php
  
namespace App\Provider;

use App\Utilities\UrlGenerator;
use GuzzleHttp\Client as Guzzle;

class ExternalAuth
{
    public const OAUTH = "https://tgstation13.org/phpBB/oauth.php";
    public const OAUTH_CREATE = "https://tgstation13.org/phpBB/oauth_create_session.php";
    public const OAUTH_INFO = "https://tgstation13.org/phpBB/oauth_get_session_info.php";

    private $guzzle;
    private $urlGenerator;

    public function __construct(Guzzle $guzzle, UrlGenerator $urlGenerator)
    {
        $this->guzzle = $guzzle;
        $this->urlGenerator = $urlGenerator;
    }

    public function createSession($token)
    {
        $response = $this->guzzle->request('GET', self::OAUTH_CREATE, [
        'query' => [
          'site_private_token' => $token,
          'return_uri' => $this->urlGenerator->fullUrlFor('auth_confirm'),
          ]
        ]);
        return json_decode($response->getBody());
    }

    public function getRedirect($public_token)
    {
        return self::OAUTH . '?' . http_build_query([
          'session_public_token' => $public_token
        ]);
    }

    public function fetchSessionInfo($site_private_token, $session_private_token)
    {
        $response = $this->guzzle->request('GET', self::OAUTH_INFO, [
          'query' => [
            'site_private_token' => $site_private_token,
            'session_private_token' => $session_private_token
          ]
        ]);
        return json_decode($response->getBody());
    }
}
