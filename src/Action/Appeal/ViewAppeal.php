<?php

namespace App\Action\Appeal;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Appeal\Service\ViewAppealService;
use App\Data\Payload;

class ViewAppeal extends Action
{
    private $viewAppeal;

    public function __construct(Responder $responder, ViewAppealService $viewAppeal)
    {
        parent::__construct($responder);
        $this->viewAppeal = $viewAppeal;
    }

    protected function action(array $args = []): Payload
    {
        $appeal = (int) $args['appeal'];
        return $this->viewAppeal->findAppeal($appeal);
    }
}
