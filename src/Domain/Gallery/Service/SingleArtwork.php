<?php

namespace App\Domain\Gallery\Service;

use App\Domain\Gallery\Service\GalleryService;

class SingleArtwork extends GalleryService
{
    public function viewSingleArtwork(string $server, string $md5)
    {
        var_dump($md5);
        if (!$this->isValidServer($server)) {
            return $this->payload->throwError(401, "Invalid server selected");
        }
        $art = $this->getArtForServer();
    }
}
