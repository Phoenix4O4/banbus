<?php

namespace App\Domain\Library\Service;

use App\Factory\SettingsFactory;
use App\Service\Service;
use App\Domain\Library\Repository\LibraryRepository as Repo;
use Symfony\Component\HttpFoundation\Session\Session;

class LibraryService extends Service
{
    public function __construct(SettingsFactory $settings, protected Repo $repo, protected Session $session)
    {
        parent::__construct($settings);
        $this->user = $this->session->get('user');
    }
}
