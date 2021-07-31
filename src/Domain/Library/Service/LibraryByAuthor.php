<?php

namespace App\Domain\Library\Service;

use App\Domain\Library\Service\LibraryService;

class LibraryByAuthor extends LibraryService
{
    public function getLibraryShelf(int $page, string $ckey)
    {
        $this->payload->addData(
            'books',
            $this->repo->getBookList($page, 60, $ckey)->getResults()
        );
        $this->payload->addData('ckey', $ckey);
        $this->payload->setTemplate('library/library.twig');
        $this->addPagination($page);
        return $this->payload;
    }

    public function countBooksByAuthor(string $ckey)
    {
        return $this->repo->countBooksByAuthor($ckey);
    }
}
