<?php

namespace App\Domain\User\Service;

use App\Service\Service;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Data\Payload;
use App\Domain\User\Repository\UserRepository as CurrentUser;
use App\Factory\SettingsFactory;
use App\Domain\User\Factory\UserFactory;
use App\Utilities\UrlGenerator;
use League\OAuth2\Client\Provider\GenericProvider;

class ForumAuthenticate extends Service
{
    public function __construct(
        private Session $session,
        private CurrentUser $user,
        SettingsFactory $settings,
        private UserFactory $userFactory,
        private UrlGenerator $url
    ) {
        $this->session = $session;
        $this->user = $user;
        $this->userFactory = $userFactory;
        parent::__construct($settings);
        $this->payload = new Payload();
        $this->config = $this->settings->getSettingsByKey('auth')['forum'];
        $this->provider = new GenericProvider([
            'clientId' => $this->config['clientId'],
            'clientSecret' => $this->config['clientSecret'],
            'redirectUri' => $this->url->fullUrlFor('auth_forum'),
            'urlAuthorize'            => 'https://tgstation13.org/phpBB/app.php/tgapi/oauth/auth',
            'urlAccessToken'          => 'https://tgstation13.org/phpBB/app.php/tgapi/oauth/token',
            'urlResourceOwnerDetails' => 'https://tgstation13.org/phpBB/app.php/tgapi/user/me',
            'accessTokenResourceOwnerId' => $this->config['scope'],
            'scopeSeparator' => ' ',
        ]);
    }

    public function beginAuthentication()
    {
        $state = bin2hex(random_bytes(32 / 2));
        $this->session->set('oauth2state', $state);
        header('Location: ' . $this->provider->getAuthorizationUrl([
            'state' => $state,
            'scope' => $this->config['scope']
        ]));
        return $this->payload;
    }
    public function confirmAuthentication(string $code, string $state)
    {
        if ($state != $this->session->get('oauth2state')) {
            $this->session->set('oauth2state', null);
            die("OAuth2 State mismatch");
        }
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        $owner = $this->provider->getResourceOwner($token);
        $owner = $owner->toArray();
        $userData = $this->user->getUserByCkey($owner['byond_ckey']);
        if (!$userData) {
            $this->payload->throwError(500, 'How did you manage this?');
            return $this->payload;
        }
        $user = $this->userFactory->BuildUser(
            $userData->ckey,
            $userData->rank,
            $userData->flags,
            $userData->feedback,
            'TGStation Forums'
        );
        $this->payload->addData('user', $user);
        $this->session->set('user', $user);
        $this->payload->setRouteRedirect('home');
        $this->session->getFlashBag()->add('Success', "You have logged in as $user->displayName via your linked TGStation Forum Account");
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
