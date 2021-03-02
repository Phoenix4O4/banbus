<?php

namespace App\Domain\Appeal\Service;

use App\Domain\Appeal\Service\AppealService;

class ViewAppealService extends AppealService
{
    public function findAppeal(int $appeal)
    {
        $appeal = $this->repo->getAppealById($appeal);
        $ban = (object) $this->bans->getSingleBan($appeal->ban)->getData()['ban'];
        $ban->appeal = $appeal;
        foreach ($ban->appeal->comments as &$c) {
            $c->author = $this->userFactory->buildUser($c->ckey, $c->rank);
        }
        if ($appeal) {
            $this->payload->addData('ban', $ban);
            $this->payload->setTemplate('bans/single.twig');
        } else {
            $this->payload->throwError(404, "This appeal could not be located");
        }
        return $this->payload;
    }
}
