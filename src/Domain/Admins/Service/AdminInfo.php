<?php

namespace App\Domain\Admins\Service;

use App\Service\Service;
use App\Data\Payload;
use App\Domain\Admins\Repository\AdminRepository as Repository;
use App\Domain\User\Factory\UserFactory;
use App\Factory\SettingsFactory;

class AdminInfo extends Service
{
    private $repository;

    public function __construct(SettingsFactory $settings, Repository $repository, UserFactory $userFactory)
    {
        parent::__construct($settings);
        $this->repository = $repository;
        $this->userFactory = $userFactory;
    }

    public function getAdminInfo()
    {
        $this->payload->addData(
            'admins',
            $this->userFactory->buildUsers(
                $this->repository->fetchAllAdmins()->getResults()
            )
        );
        $this->payload->addData(
            'perms',
            $this->settings->getSettingsByKey('perm_flags')
        );
        $this->payload->setTemplate('admins/adminwho.twig');
        return $this->payload;
    }
}
