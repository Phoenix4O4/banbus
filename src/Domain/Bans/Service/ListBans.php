<?php

namespace App\Domain\Bans\Service;

use App\Service\Service;
use App\Data\Payload;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Domain\Bans\Repository\BanRepository as Repository;

class ListBans extends Service
{
    private $session;
    private $banRepository;

    public function __construct(Session $session, Repository $banRepository)
    {
        $this->session = $session;
        $this->banRepository = $banRepository;
        $this->payload = new Payload();
    }

    public function getBansForCurrentUser()
    {
        if (!$this->session->get('user')) {
            $this->payload->throwError(403, "You must be logged in to access this page.");
            return $this->payload;
        }
        $this->payload->addData(
            'bans',
            $this->banRepository->getBansByCkey(
                $this->session->get('user')->getCkey()
            )
        );
        return $this->payload;
    }

    public function getSingleBanForCurrentUser($id)
    {
        if (!$this->session->get('user')) {
            $this->payload->throwError(403, "You must be logged in to access this page.");
            return $this->payload;
        }
        $ban = $this->banRepository->getSingleBanByCkey(
            $this->session->get('user')->getCkey(),
            $id
        );
        if (!$this->payload->addData('ban', $ban)) {
            $this->payload->throwError(404, "Ban with id $id not found");
        }
        return $this->payload;
    }

    public function getPublicBans()
    {
        $this->payload->addData(
            'bans',
            $this->banRepository->getPublicBans()
        );
        return $this->payload;
    }

    public function getBan(int $id)
    {
        $ban = $this->banRepository->getBanById($id);
        if (!$this->payload->addData('ban', $ban)) {
            $this->payload->throwError(404, "Ban with id $id not found");
        }
        return $this->payload;
    }
}
