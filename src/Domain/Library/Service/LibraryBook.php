<?php

namespace App\Domain\Library\Service;

use App\Domain\Library\Service\LibraryService;

class LibraryBook extends LibraryService
{
    public function getBook(int $ntbn, bool $moderate = false)
    {
        $this->payload->setTemplate('library/single.twig');
        $this->book = $this->repo->getSingleBook($ntbn)->getResults();
        if ($this->book->deleted && $this->user && $this->user->hasPermission('BAN')) {
            $this->payload->addData('book', $this->book);
        } elseif (!$this->book->deleted) {
            if (!$this->user || !$this->user->hasPermission('BAN')) {
                $this->book->clearModLog();
            }
            $this->payload->addData('book', $this->book);
        } else {
            $this->payload->throwError(451, "This book has been deleted");
        }
        if ($moderate) {
            $this->moderateBook($ntbn);
        }
        return $this->payload;
    }

    public function moderateBook()
    {
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        if (isset($_POST['delete'])) {
            $this->deleteBook($reason);
        } else {
            $this->reportBook($reason);
        }
    }

    private function deleteBook($reason): void
    {
        if (!$this->user->hasPermission('BAN')) {
            die("Not allowed");
        }
        if ($this->book->deleted) {
            $this->repo->setBookUndeleted($this->book->id, $reason, $this->user->getCkey());
            $this->payload->addSuccessMessage("This book has been undeleted");
            $this->book->deleted = false;
            return;
        } else {
            $this->repo->setBookDeleted($this->book->id, $reason, $this->user->getCkey());
            $this->payload->addSuccessMessage("This book has been deleted");
            $this->book->deleted = true;
            return;
        }
    }

    private function reportBook($reason):void
    {
        if (!$this->user) {
            die("You must be authenticated");
        }
        $this->repo->addBookReport($this->book->id, $reason, $this->user->getCkey());
        $this->payload->addSuccessMessage("Your report has been filed.");
    }
}
