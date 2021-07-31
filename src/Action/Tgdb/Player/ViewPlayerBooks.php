<?php

namespace App\Action\Tgdb\Player;

use App\Action\Action;
use App\Responder\Responder;
use App\Domain\Library\Service\LibraryByAuthor as Service;
use App\Data\Payload;

class ViewPlayerBooks extends Action
{
    public $template = 'library/index.twig';
    public function __construct(Responder $responder, private Service $library)
    {
        parent::__construct($responder);
    }

    public function action(array $args = []): Payload
    {
        $page = (isset($args['page'])) ? (int) $args['page'] : 1;
        $ckey = filter_var(
            $args['ckey'],
            FILTER_SANITIZE_STRING,
            FILTER_FLAG_STRIP_HIGH
        );
        return $this->library->getLibraryShelf($page, $ckey);
    }
}
