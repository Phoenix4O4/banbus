<?php

namespace App\Domain\Library\Repository;

use App\Repository\Database;
use App\Domain\Library\Data\Library;
use App\Utilities\HTMLFactory;
use DateTime;
use Exception;

class LibraryRepository extends Database
{
    public function getBookList(int $page = 1, int $per_page = 60, bool|string $ckey = false): self
    {
        $where = '';
        if ($ckey) {
            $where = "WHERE ckey = ?";
            $args[] = $ckey;
            $this->setPages((int) ceil($this->db->cell("SELECT count(library.id) as count FROM library WHERE ckey = ?", $ckey) / $per_page));
        } else {
            $where = "WHERE deleted = 0 OR ISNULL(deleted)";
            $this->setPages((int) ceil($this->db->cell(
                "SELECT
                count(library.id)
                FROM library
                WHERE deleted = 0
                OR deleted IS NULL"
            ) / $per_page));
        }
        $args[] = ($page * $per_page) - $per_page;
        $args[] = $per_page;
        //TODO: Rework this so we can show deleted books to admins
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
            $where
            ORDER BY `datetime` DESC
            LIMIT ?,?",
            ...$args
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

    public function searchLibrary(int $page = 1, int $per_page = 60, string $term = '')
    {
        $term = filter_var($term, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $statement = \ParagonIE\EasyDB\EasyStatement::open()->andWith('AND library.content like ?', '%' . $this->db->escapeLikeValue($term) . '%');
        $this->setPages((int) ceil($this->db->cell(
            "SELECT
        count(library.id)
        FROM library
        WHERE deleted = 0
        OR deleted IS NULL
        $statement",
            $statement->values()[0]
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
            WHERE (deleted = 0
            OR deleted IS NULL)
            $statement
            ORDER BY `datetime` DESC
            LIMIT ?,?",
            $statement->values()[0],
            ($page * $per_page) - $per_page,
            $per_page
        ));
        $tmp = [];
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
        try {
            $this->db->insert('library_action', [
            'book' => $ntbn,
            'reason' => $reason,
            'action' => $action,
            'ckey' => $ckey,
            'ip_addr' => ip2long($_SERVER['REMOTE_ADDR'])
        ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function addBookReport(int $ntbn, string $reason, string $ckey)
    {
        $this->updateBookLog($ntbn, $reason, 'reported', $ckey);
    }

    public function countBooksByAuthor(string $ckey)
    {
        return $this->db->cell("SELECT count(id) FROM library WHERE ckey = ?", $ckey);
    }

    private function getModerationLog(int $ntbn)
    {
        return $this->db->run("SELECT library_action.*,
        a.rank
        FROM library_action
        LEFT JOIN `admin` a ON a.ckey = library_action.ckey
        WHERE book = ?
        ORDER BY library_action.datetime DESC", $ntbn);
    }
}
