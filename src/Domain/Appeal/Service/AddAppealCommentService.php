<?php

namespace App\Domain\Appeal\Service;

use App\Domain\Appeal\Service\AppealService;

class AddAppealCommentService extends AppealService
{
    public function addNewComment(string $text, int $appeal)
    {
        $user = $this->session->get('user');
        if (
            true === $result = $this->repo->addNewAppealComment(
                $appeal,
                $text,
                $user->getCkey(),
                $user->rank->title
            )
        ) {
            $this->session->getFlashBag()->add('Success', "Your comment was added!");
            $this->payload->setRouteRedirect('appeal', ['appeal' => $appeal]);

            return $this->payload;
        } else {
            $this->payload->throwError(500, $result);
            return $this->payload;
        }
    }
}
