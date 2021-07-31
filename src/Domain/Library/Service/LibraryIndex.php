<?php

namespace App\Domain\Library\Service;

use App\Domain\Library\Service\LibraryService;

class LibraryIndex extends LibraryService
{
    public function getLibraryShelf($page)
    {
        $this->payload->addData('books', $this->repo->getBookList($page)->getResults());
        $this->payload->setTemplate('library/library.twig');
        $this->addPagination($page);
        return $this->payload;
    }
}
