<?php

namespace App\Domain\Appeal\Service;

use App\Service\Service;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Factory\SettingsFactory;
use App\Domain\Appeal\Repository\AppealRepository;
use App\Domain\Bans\Service\ListBans;
use App\Domain\User\Factory\UserFactory;

class AppealService extends Service
{
    protected $session;
    protected $repo;
    protected $bans;
    protected $userFactory;

    public function __construct(
        SettingsFactory $settings,
        Session $session,
        AppealRepository $repo,
        ListBans $bans,
        UserFactory $userFactory
    ) {
        parent::__construct($settings);
        $this->session = $session;
        $this->repo = $repo;
        $this->bans = $bans;
        $this->userFactory = $userFactory;
    }
}
