<?php

namespace App\Utilities;

use HTMLPurifier;

class HTMLFactory
{
    public $purifier;

    public function __construct()
    {
        require_once __DIR__ . '/../../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';
        $config = \HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
    }

    public function sanitizeString(string $string): string
    {
        return $this->purifier->purify($string);
    }
}
