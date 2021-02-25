<?php

namespace App\Domain\Messages\Service;

use App\Service\Service;
use App\Factory\SettingsFactory;
use App\Domain\Messages\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Domain\User\Factory\UserFactory;
use App\Domain\Servers\Data\Server;
use App\Data\Payload;

class GetMessages extends Service
{
    private $repo;
    private $session;
    private $user;

    public function __construct(SettingsFactory $settings, Session $session, MessageRepository $repo, UserFactory $user)
    {
        parent::__construct($settings);
        $this->session = $session;
        $this->repo = $repo;
        $this->user = $user;
        $this->servers = $settings->getSettingsByKey('servers');
    }

    public function getMessagesForCurrentUser(int $page = 1): Payload
    {
        if (!$ckey = $this->session->get('user')->getCkey()) {
            $this->payload->throwError(403, "You must be logged in to access this page.");
            return $this->payload;
        }
        $notes = $this->repo->getMessagesByCkey($ckey, true, $page, $this->per_page)->getResults();
        foreach ($notes as &$n) {
            $n->target = $this->user->buildUser($n->targetckey, $n->targetrank);
            $n->admin = $this->user->buildUser($n->adminckey, $n->adminrank);
            if ($n->lasteditor) {
                $n->editor = $this->user->buildUser($n->lasteditor, $n->editorrank);
            } else {
                $n->editor = false;
            }
            $n->server = Server::fromJson($this->mapServer($n->server_ip, $n->server_port));
        }
        $this->payload->addData(
            'notes', //Messages is already a key!
            $notes
        );
        $this->payload->addData(
            'pagination',
            [
                'pages' => $this->repo->getPages(),
                'currentPage' => $page
            ]
        );
        $this->payload->setTemplate('messages/mymessages.twig');
        return $this->payload;
    }
    public function getMessagesForPlayer(string $ckey, int $page): Payload
    {
        $notes = $this->repo->getMessagesByCkey($ckey, false, $page, $this->per_page)->getResults();
        foreach ($notes as &$n) {
            $n->target = $this->user->buildUser($n->targetckey, $n->targetrank);
            $n->admin = $this->user->buildUser($n->adminckey, $n->adminrank);
            if ($n->lasteditor) {
                $n->editor = $this->user->buildUser($n->lasteditor, $n->editorrank);
            } else {
                $n->editor = false;
            }
            $n->server = Server::fromJson($this->mapServer($n->server_ip, $n->server_port));
        }
        $this->payload->addData(
            'ckey',
            $ckey
        );

        $this->payload->addData(
            'notes', //Messages is already a key!
            $notes
        );
        $this->payload->addData(
            'pagination',
            [
                'pages' => $this->repo->getPages(),
                'currentPage' => $page
            ]
        );
        $this->payload->setTemplate('messages/playermessages.twig');
        return $this->payload;
    }
}
