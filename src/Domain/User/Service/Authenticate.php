<?php

namespace App\Domain\User\Service;

use App\Service\Service;
use App\Provider\ExternalAuth;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Data\Payload;
use App\Domain\User\Data\User;
use App\Domain\User\Repository\UserRepository as CurrentUser;

class Authenticate extends Service
{
    private $auth;
    private $session;
    private $user;

    public function __construct(Session $session, ExternalAuth $auth, CurrentUser $user)
    {
        $this->session = $session;
        $this->auth = $auth;
        if (!$this->session->get('site_private_token')) {
            $this->session->invalidate();
            $this->session->start();
            $this->session->set('site_private_token', $this->generateToken());
        }
        $this->user = $user;
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
        $response = $this->auth->fetchSessionInfo(
            $this->session->get('site_private_token'),
            $this->session->get('session_private_token')
        );
        if ("OK" != $response->status) {
            die($response->error);
        }
        $user = new User($response->byond_ckey, $this->user->getUserRank($response->byond_ckey)->rank);
        $this->payload->addData('user', $user);
        $this->session->set('user', $user);
        return $this->payload;
    }

    public function destroySession()
    {
        $this->session->set('user', false);
        $this->session->invalidate();
        $this->payload->addData('user', false);
        return $this->payload;
    }

    private function generateToken(bool $secure = true)
    {
        $r_bytes = openssl_random_pseudo_bytes(5120, $secure);
        return hash('sha512', $r_bytes);
    }
}
