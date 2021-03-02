<?php

namespace App\Domain\Player\Service;

use App\Data\Payload;
use App\Domain\Player\Repository\ViewPlayerRepository;
use App\Domain\User\Factory\UserFactory;
use App\Domain\Bans\Service\ListBans;
use App\Domain\Messages\Service\GetMessages;

class ViewPlayer
{
    private $ViewPlayerRepository;
    private $userFactory;
    private $bans;
    private $messages;

    public function __construct(
        ViewPlayerRepository $ViewPlayerRepository,
        UserFactory $userFactory,
        ListBans $bans,
        GetMessages $messages
    ) {
        $this->ViewPlayerRepository = $ViewPlayerRepository;
        $this->userFactory = $userFactory;
        $this->payload = new Payload();
        $this->bans = $bans;
        $this->messages = $messages;
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
        $player->standing = $this->bans->isPlayerBanned($player->ckey);
        $player->messages = $this->messages->getPlayerMessageCount($player->ckey);
        $this->payload->addData(
            'player',
            $player
        );
        $this->payload->setTemplate('player/player.twig');
        if (isset($_GET['format']) && 'popover' === $_GET['format']) {
            $this->payload->setTemplate('standalone/player.twig');
        }
        return $this->payload;
    }
}
