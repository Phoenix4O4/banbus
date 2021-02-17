<?php

namespace App\Domain\Admins\Service;

use App\Service\Service;
use App\Data\Payload;
use App\Domain\Admins\Repository\AdminLogRepository as Repository;
use App\Domain\User\Factory\UserFactory;
use App\Factory\SettingsFactory;

class AdminRankLog extends Service
{
    private $repository;

    public function __construct(SettingsFactory $settings, Repository $repository, UserFactory $userFactory)
    {
        parent::__construct($settings);
        $this->repository = $repository;
        $this->userFactory = $userFactory;
    }

    public function getAdminRankLogs(int $page = 1)
    {
        $logs = $this->repository->fetchAdminLogs($page)->getResults();
        foreach ($logs as &$l) {
            $l->admin = $this->userFactory->buildUser($l->adminckey, $l->adminrank);
            $l->icon = 'edit';
            switch ($l->operation) {
                case 'add admin':
                    $l->class = 'success';
                    $l->icon  = 'user-plus';
                    break;
                case 'remove admin':
                    $l->class = 'danger';
                    $l->icon  = 'user-times';
                    break;
                case 'change admin rank':
                    $l->class = 'info';
                    $l->icon  = 'user-tag';
                    break;
                case 'add rank':
                    $l->class = 'success';
                    $l->icon  = 'plus-square';
                    break;
                case 'remove rank':
                    $l->class = 'warning';
                    $l->icon  = 'minus-square';
                    break;
                case 'change rank flags':
                    $l->class = 'primary';
                    $l->icon  = 'flag';
                    break;
            }
        }
        $this->payload->addData(
            'logs',
            $logs
        );
        $this->payload->addData(
            'pagination',
            [
                'pages' => $this->repository->getPages(),
                'currentPage' => $page
            ]
        );

        $this->payload->setTemplate('admins/ranklog.twig');
        return $this->payload;
    }
}
