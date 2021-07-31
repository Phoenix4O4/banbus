<?php

namespace App\Domain\Library\Repository;

use App\Repository\Database;
use App\Domain\Library\Data\Library;
use App\Utilities\HTMLFactory;
use DateTime;

class LibraryRepository extends Database
{
    public function getBookList(int $page = 1, int $per_page = 60): self
    {
        $this->setPages((int) ceil($this->db->cell(
            "SELECT
          count(library.id)
          FROM library
          WHERE deleted = 0
        OR deleted IS NULL"
        ) / $per_page));

        $this->setResults($this->db->run(
            "SELECT 
        id, 
        author,
        title,
        category,
        `datetime`,
        SUBSTRING(`content`, 1, 512) as content,
        IFNULL(deleted, 0) as deleted,
        ckey
        FROM library
        WHERE deleted = 0
        OR deleted IS NULL
        ORDER BY `datetime`
        DESC LIMIT ?,?",
            ($page * $per_page) - $per_page,
            $per_page
        ));
        foreach ($this->getResults() as &$r) {
            $r->datetime = new DateTime($r->datetime);
            $r->content = strip_tags($r->content);
            $r->modLog = null;
            $tmp[] = Library::new($r);
        }
        $this->setResults($tmp);
        return $this;
    }

    public function getSingleBook(int $ntbn): self
    {
        $r = $this->db->row("SELECT
        id,
        author,
        title,
        category,
        `datetime`, 
        content,
        IFNULL(deleted, 0) as deleted,
        ckey
        FROM library
        WHERE id = ?", $ntbn);
        $r->datetime = new DateTime($r->datetime);
        $r->modLog = $this->getModerationLog($r->id);
        $this->setResults(Library::new($r));

        return $this;
    }

    public function toggleBook($id)
    {
        $this->db->run("UPDATE library SET deleted = 1 - deleted WHERE id = ?", $id);
    }

    public function setBookUndeleted(int $ntbn, string $reason, string $ckey)
    {
        // $this->toggleBook($ntbn);
        $this->db->update("library", [
            'deleted' => 0
        ], [
            'id' => $ntbn
        ]);
        $this->updateBookLog($ntbn, $reason, 'undeleted', $ckey);
    }

    public function setBookDeleted(int $ntbn, string $reason, string $ckey)
    {
        // $this->toggleBook($ntbn);
        $this->db->update("library", [
            'deleted' => 1
        ], [
            'id' => $ntbn
        ]);
        $this->updateBookLog($ntbn, $reason, 'deleted', $ckey);
    }

    private function updateBookLog(int $ntbn, string $reason, string $action, string $ckey)
    {
        $this->db->insert('library_action', [
            'book' => $ntbn,
            'reason' => $reason,
            'action' => $action,
            'ckey' => $ckey,
            'ip_addr' => ip2long($_SERVER['REMOTE_ADDR'])
        ]);
    }

    public function addBookReport(int $ntbn, string $reason, string $ckey)
    {
        $this->updateBookLog($ntbn, $reason, 'reported', $ckey);
    }

    private function getModerationLog(int $ntbn)
    {
        return $this->db->run("SELECT library_action.*,
        a.rank
        FROM library_action
        LEFT JOIN `admin` a ON a.ckey = library_action.ckey
        WHERE book = ?", $ntbn);
    }
}
