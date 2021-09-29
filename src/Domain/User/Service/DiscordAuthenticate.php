<?php

namespace App\Domain\User\Service;

use App\Service\Service;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Data\Payload;
use App\Domain\User\Repository\UserRepository as CurrentUser;
use App\Factory\SettingsFactory;
use App\Domain\User\Factory\UserFactory;
use App\Utilities\UrlGenerator;
use Wohali\OAuth2\Client\Provider\Discord;

class DiscordAuthenticate extends Service
{
    private $session;
    private $user;
    private $userFactory;

    public function __construct(
        Session $session,
        CurrentUser $user,
        SettingsFactory $settings,
        UserFactory $userFactory,
        private UrlGenerator $url
    ) {
        $this->session = $session;
        $this->user = $user;
        $this->userFactory = $userFactory;
        parent::__construct($settings);
        $this->payload = new Payload();
        $this->config = $this->settings->getSettingsByKey('auth')['discord'];
        $this->config['redirectUri'] = $this->url->fullUrlFor('discord_auth');
        $this->discord = new Discord($this->config);
    }

    public function beginAuthentication()
    {
        $state = bin2hex(random_bytes(32 / 2));
        $this->session->set('oauth2state', $state);
        header('Location: ' . $this->discord->getAuthorizationUrl([
            'state' => $state,
            'scope' => $this->config['scope']
        ]));
    }
    public function confirmAuthentication(string $code, string $state)
    {
        if ($state != $this->session->get('oauth2state')) {
            $this->session->set('oauth2state', null);
            die("OAuth2 State mismatch");
        }
        $token = $this->discord->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        $owner = $this->discord->getResourceOwner($token);
        $owner = $owner->toArray();
        $userData = $this->user->getUserByDiscordId($owner['id']);
        if (!$userData) {
            $this->payload->throwError(500, 'Unable to locate your Discord account in the database. Have you [Linked your account](https://tgstation13.org/wiki/Discord_Verification)?');
            return $this->payload;
        }
        $user = $this->userFactory->BuildUser(
            $userData->ckey,
            $userData->rank,
            $userData->flags,
            $userData->feedback
        );
        $this->payload->addData('user', $user);
        $this->session->set('user', $user);
        $this->payload->setRouteRedirect('home');
        $this->session->getFlashBag()->add('Success', "You have logged in as $user->displayName via your linked Discord Account");
        if ($this->session->has('destination_route')) {
            $this->payload->setRouteRedirect($this->session->get('destination_route'));
            $this->session->remove('destination_route');
        }
        if ($this->session->has('destination_uri')) {
            $this->payload->setRedirect($this->session->get('destination_uri'));
            $this->session->remove('destination_uri');
        }

        return $this->payload;
    }
}
