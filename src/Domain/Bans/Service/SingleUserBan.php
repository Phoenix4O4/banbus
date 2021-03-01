<?php

namespace App\Domain\Bans\Service;

use App\Domain\Bans\Service\ListBans;

class SingleUserBan extends ListBans
{
    public function getSingleBanForCurrentUser(int $id, ?array $appeal = null)
    {
        $this->payload->setTemplate('bans/single.twig');
        $this->payload->addData('unbaninfo', true);
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
        if (!$ban) {
            $this->payload->throwError(404, "Ban with id $id not found");
            return $this->payload;
        }
        $appeal_status = $this->banRepository->checkForActiveAppeal($ban->id)->getResults();
        $ban = $this->banFactory->buildBan($ban, $appeal_status);
        if (!$this->payload->addData('ban', $ban)) {
            $this->payload->throwError(404, "Ban with id $id not found");
        }

        if ($appeal) {
            //Validate appeal
            $appeal = $this->validateAppeal($appeal);
            if (!is_array($appeal)) {
                $this->payload->addErrorMessage($appeal);
            } else {
                //Attempt to start a new appeal
                $newAppeal = $this->banRepository->createNewAppeal($ban->id, $appeal);
                if (true === $newAppeal) {
                    $this->payload->addSuccessMessage("Your appeal has been created");
                } else {
                    $this->payload->addErrorMessage(500, $newAppeal);
                }
            }
        }
        return $this->payload;
    }

    private function validateAppeal($appeal)
    {
        if (empty($appeal['character'])) {
            return "You must specify a character name. All fields are required.";
        }
        if (empty($appeal['story'])) {
            return "You must explain your side of the story. All fields are required.";
        }
        if (empty($appeal['why'])) {
            return "You must explain why you wish to be unbanned. All fields are required.";
        }
        return $appeal;
    }
}
