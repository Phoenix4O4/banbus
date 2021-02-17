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
        $player->userData = $this->userFactory->BuildUser(
            $player->ckey,
            $player->rank,
            $player->perms
        );

        if (!$player) {
            $this->payload->throwError(404, "This ckey could not be found");
            return $this->payload;
        }
        $this->payload->addData(
            'player',
            $player
        );
        $this->payload->setTemplate('player/player.twig');
        return $this->payload;
    }
}
