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
            $n = $this->processNote($n);
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
    public function getSingleMessageForCurrentUser(int $id)
    {
        if (!$ckey = $this->session->get('user')->getCkey()) {
            $this->payload->throwError(403, "You must be logged in to access this page.");
            return $this->payload;
        }
        $note = $this->repo->getSingleMessageById($id)->getResults();
        if ($ckey != $note->targetckey || $note->secret) {
            $this->payload->throwError(403, "You do not have permissioon to access this.");
            return $this->payload;
        }

        $note = $this->processNote($note);

        $this->payload->addData(
            'note', //Messages is already a key!
            $note
        );
        $this->payload->addData('link', 'mymessages.single');

        $this->payload->setTemplate('messages/single.twig');
        return $this->payload;
    }
    public function getMessagesForPlayer(string $ckey, int $page): Payload
    {
        $notes = $this->repo->getMessagesByCkey($ckey, false, $page, $this->per_page)->getResults();
        foreach ($notes as &$n) {
            $n = $this->processNote($n);
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

    public function getSingleMessage(int $id)
    {
        $note = $this->repo->getSingleMessageById($id)->getResults();
        $note = $this->processNote($note);


        $this->payload->addData(
            'note', //Messages is already a key!
            $note
        );
        $this->payload->addData('link', 'tgdb.message');
        $this->payload->setTemplate('messages/single.twig');
        return $this->payload;
    }

    public function getPlayerMessageCount(string $ckey)
    {
        return $this->repo->countActiveMessagesForPlayer($ckey);
    }

    private function processNote(object $note): object
    {
        $note->target = $this->user->buildUser($note->targetckey, $note->targetrank);
        $note->admin = $this->user->buildUser($note->adminckey, $note->adminrank);
        if ($note->lasteditor) {
            $note->editor = $this->user->buildUser($note->lasteditor, $note->editorrank);
        } else {
            $note->editor = false;
        }
        $note->server = Server::fromJson($this->mapServer($note->server_ip, $note->server_port));
        return $note;
    }
}
