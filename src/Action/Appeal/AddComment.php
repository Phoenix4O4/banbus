<?php

namespace App\Action\Appeal;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Appeal\Service\AddAppealCommentService;
use App\Data\Payload;

class AddComment extends Action
{
    private $addComment;

    public function __construct(Responder $responder, AddAppealCommentService $addComment)
    {
        parent::__construct($responder);
        $this->addComment = $addComment;
    }

    protected function action(array $args = []): Payload
    {
        $appeal = (int) $args['appeal'];
        $comment = $this->request->getParsedBody()['comment'];
        return $this->addComment->addNewComment($comment, $appeal);
    }
}
