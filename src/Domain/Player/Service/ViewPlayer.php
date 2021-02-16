<?php

namespace App\Domain\Player\Service;

use App\Data\Payload;
use App\Domain\Player\Repository\ViewPlayerRepository;
use App\Domain\User\Factory\UserFactory;

class ViewPlayer
{
    private $ViewPlayerRepository;
    private $userFactory;

    public function __construct(
        ViewPlayerRepository $ViewPlayerRepository,
        UserFactory $userFactory
    ) {
        $this->ViewPlayerRepository = $ViewPlayerRepository;
        $this->userFactory = $userFactory;
        $this->payload = new Payload();
    }

    public function getPlayerInformation(string $ckey): Payload
    {
        $player = $this->ViewPlayerRepository->getPlayerByCkey($ckey);
        // $player = $this->userFactory->BuildUser(
        //     $player->ckey,
        //     $player->rank,
        //     $player->perms
        // );

        $this->payload->addData(
            'player',
            $player
        );
        $this->payload->setTemplate('tgdb/player.twig');
        return $this->payload;
    }
}
