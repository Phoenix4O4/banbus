<?php

namespace App\Domain\Gallery\Service;

use App\Domain\Gallery\Service\GalleryService;

class SingleArtwork extends GalleryService
{
    public function viewSingleArtwork(string $server, string $md5)
    {
        $art = $this->getSingleArtwork($server, $md5);
        $this->payload->setTemplate('gallery/single.twig');
        return $this->payload->addData('art', $art);
    }
}
