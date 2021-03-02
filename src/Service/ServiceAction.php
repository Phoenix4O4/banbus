<?php

namespace App\Service;

use App\Data\Payload;

interface ServiceAction
{
    public function action(): Payload;
}
