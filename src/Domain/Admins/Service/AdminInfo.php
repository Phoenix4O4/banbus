<?php

namespace App\Domain\Admins\Service;

use App\Service\Service;
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

    public function getAdminPlaytime(string $ckey)
    {
        $admin = $this->repository->fetchAdmin($ckey)->getResults();
        if (!$admin) {
            $this->payload->throwError(404, "This ckey could not be located");
            return $this->payload;
        }
        $admin = $this->userFactory->buildUser($admin->ckey, $admin->rank);
        $this->payload->setTemplate('admins/playtime.twig');
        $this->payload->addData('admin', $admin);
        $this->payload->addData('playtime', $this->repository->getPlaytimeForAdmin($ckey));
        return $this->payload;
    }
}
