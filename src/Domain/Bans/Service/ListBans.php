<?php

namespace App\Domain\Bans\Service;

use App\Service\Service;
use App\Data\Payload;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Domain\Bans\Repository\BanRepository as Repository;
use App\Factory\SettingsFactory;
use App\Domain\Bans\Factory\BanFactory;
use stdClass;

class ListBans extends Service
{
    private $session;
    private $banRepository;
    private $banFactory;

    public function __construct(Session $session, Repository $banRepository, SettingsFactory $settings, BanFactory $banFactory)
    {
        $this->session = $session;
        $this->banRepository = $banRepository;
        $this->payload = new Payload();
        $this->banFactory = $banFactory;
        parent::__construct($settings);
    }

    public function getBansForCurrentUser()
    {
        if (!$this->modules['personal_bans']) {
            $this->payload->throwError(500, "This module is not enabled");
            return $this->payload;
        }
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
        $this->payload->setTemplate('bans/mybans.twig');
        return $this->payload;
    }

    public function getSingleBanForCurrentUser($id)
    {
        //Module disabled kick out
        if (!$this->modules['personal_bans']) {
            $this->payload->throwError(500, "This module is not enabled");
            return $this->payload;
        }

        //Not logged in kick out
        if (!$this->session->get('user')) {
            $this->session->set('destination_route', 'banbus.index');
            $this->payload->throwError(403, "You must be logged in to access this page.");
            return $this->payload;
        }
        $ban = $this->banRepository->getSingleBanByCkey(
            $this->session->get('user')->getCkey(),
            $id
        );
        $ban = $this->banFactory->buildBan($ban);
        $this->payload->setTemplate('bans/single.twig');
        $this->payload->addData('unbaninfo', true);
        if (!$this->payload->addData('ban', $ban)) {
            $this->payload->throwError(404, "Ban with id $id not found");
        }
        return $this->payload;
    }

    public function getPublicBans()
    {
        if (!$this->modules['public_bans']) {
            $this->payload->throwError(500, "This module is not enabled");
            return $this->payload;
        }
        $this->payload->addData(
            'bans',
            $this->banRepository->getPublicBans()
        );
        $this->payload->setTemplate('bans/bans.twig');
        return $this->payload;
    }

    public function getBan(int $id)
    {
        if (!$this->modules['public_bans']) {
            $this->payload->throwError(500, "This module is not enabled");
            return $this->payload;
        }
        $ban = $this->banRepository->getBanById($id);
        if (!$this->payload->addData('ban', $ban)) {
            $this->payload->throwError(404, "Ban with id $id not found");
            $this->payload->setTemplate('bans/single.twig');
        }
        return $this->payload;
    }

    public function bansHomepage()
    {
        if (!$this->session->get('user') && !$this->modules['public_bans']) {
            $this->payload->setTemplate('banbus/banbus.twig');
            $this->session->set('destination_route', 'banbus.index');
            return $this->payload;
        } elseif ($this->session->get('user') && $this->modules['personal_bans']) {
            return $this->getBansForCurrentUser();
        }
    }

    public function getBansForCkey(string $ckey, int $page = 1)
    {
        $this->payload->addData(
            'bans',
            $this->banRepository->getBansByCkey($ckey, $page)
        );
        $this->payload->addData('ckey', $ckey);
        $this->payload->setTemplate('bans/tgdblistbans.twig');
        return $this->payload;
    }

    public function getSingleBan(int $id)
    {
        $ban = $this->banRepository->getBanById($id);
        $ban = $this->banFactory->buildBan($ban);
        $this->payload->addData(
            'ban',
            $ban
        );
        $this->payload->setTemplate('bans/single.twig');

        return $this->payload;
    }

    public function isPlayerBanned($ckey)
    {
        $standing = new stdClass();
        $standing->bans = $this->banRepository->getPlayerStanding($ckey)->getResults();
        if (!$standing->bans) {
            $standing->class = 'success';
            $standing->text  = 'Not Banned';
            return $standing;
        }

        foreach ($standing->bans as $b) {
            $b->perm = (isset($b->expiration_time)) ? false : true;
        }
        if ($b->perm && 'Server' === $b->role) {
            $standing->class = 'perma';
            $standing->text = 'Permabanned';
        } else {
            $standing->class = 'danger';
            $standing->text = 'Active Bans';
        }
        return $standing;
    }
}
