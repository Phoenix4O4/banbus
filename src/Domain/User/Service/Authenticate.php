<?php

namespace App\Domain\User\Service;

use App\Service\Service;
use App\Provider\ExternalAuth;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Data\Payload;

class Authenticate extends Service
{
    private $auth;
    private $session;

    public function __construct(Session $session, ExternalAuth $auth)
    {
        $this->session = $session;
        $this->auth = $auth;
        if (!$this->session->get('site_private_token')) {
            $this->session->invalidate();
            $this->session->start();
            $this->session->set('site_private_token', $this->generateToken());
        }
        $this->payload = new Payload();
    }
    public function beginAuthentication()
    {
        $response = $this->auth->createSession(
            $this->session->get('site_private_token')
        );
        $this->session->set(
            'session_private_token',
            $response->session_private_token
        );
        $this->session->set(
            'session_public_token',
            $response->session_public_token
        );
        $this->payload->setRedirect($this->auth->getRedirect(
            $this->session->get('session_public_token')
        ));
        return $this->payload;
    }

    public function confirmAuthentication()
    {
        $this->payload->addData('user', $this->auth->fetchSessionInfo(
            $this->session->get('site_private_token'),
            $this->session->get('session_private_token')
        ));
        return $this->payload;
    }

    private function generateToken(bool $secure = true)
    {
        $r_bytes = openssl_random_pseudo_bytes(5120, $secure);
        return hash('sha512', $r_bytes);
    }
}
