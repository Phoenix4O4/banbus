<?php

namespace App\Domain\Library\Data;

use DateTime;
use App\Utilities\HTMLFactory;

class Library
{
    private $HTMLFactory;

    public function __construct(
        public int $id,
        public string $author,
        public string $title,
        public string $category,
        public DateTime $datetime,
        public ?string $content,
        public bool $deleted,
        public ?array $modLog,
        public string $ckey
    ) {
        $this->HTMLFactory = new HTMLFactory();
        $this->title = $this->HTMLFactory->sanitizeString($this->title);
        $this->content = $this->HTMLFactory->sanitizeString($this->content);
        $this->author = $this->HTMLFactory->sanitizeString($this->author);
        $this->deleted = (bool) $this->deleted;
    }

    public function clearModLog()
    {
        $this->modLog = null;
    }

    public static function new(object $row)
    {
        return new self(
            ...get_object_vars($row)
        );
    }
}
