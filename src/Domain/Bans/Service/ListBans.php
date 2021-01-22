<?php

namespace App\Domain\Bans\Service;

use App\Service\Service;
use App\Data\Payload;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Domain\Bans\Repository\UserBansRepository as Repository;

class ListBans extends Service
{
    private $session;
    private $userBansRepository;

    public function __construct(Session $session, Repository $userBansRepository)
    {
        $this->session = $session;
        $this->userBansRepository = $userBansRepository;
        $this->payload = new Payload();
    }

    public function getBansForCurrentUser()
    {
        $this->payload->addData(
            'bans',
            $this->userBansRepository->getBansByCkey(
                $this->session->get('user')->getCkey()
            )
        );
        return $this->payload;
    }
}
