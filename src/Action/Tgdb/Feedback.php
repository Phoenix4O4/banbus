<?php

namespace App\Action\Tgdb;

use App\Action\Action;
use App\Data\Payload;
use App\Domain\Admins\Service\FeedbackUpdater;
use App\Responder\Responder;

/**
 * Action.
 */
final class Feedback extends Action
{
    private $feedback;

    public function __construct(Responder $responder, FeedbackUpdater $feedback)
    {
        parent::__construct($responder);
        $this->feedback = $feedback;
    }
    public function action(array $args = []): Payload
    {
        if ('POST' === $this->request->getMethod()) {
            $this->feedback->updateFeedbackLink($this->request->getParsedBody()['link']);
        }
        return $this->feedback->feedbackUpdater();
    }
}
