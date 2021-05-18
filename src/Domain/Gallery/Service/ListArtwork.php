<?php

namespace App\Domain\Gallery\Service;

use App\Domain\Gallery\Service\GalleryService;

class ListArtwork extends GalleryService
{
    public function getArtWorkForServer(string $server)
    {
        if (!$this->isValidServer($server)) {
            return $this->payload->throwError(401, "Invalid server selected");
        }
        $art = $this->parseArt($this->getArtForServer());
        $this->payload->setTemplate('gallery/gallery.twig');
        $this->payload->addData('server', $this->server);
        return $this->payload->addData('art', (array) $art);
    }

    private function parseArt($art)
    {
        $ratings = $this->repo->getRatingsForServer($this->server->name)->getResults();
        foreach ($art as $gallery => &$artwork) {
            foreach ($artwork as &$a) {
                foreach ($ratings as $r) {
                    if ($a->md5 === $r->artwork) {
                        $a->rating = (float) $r->rating;
                        $a->votes = $r->votes;
                    }
                }
                $a->url = $this->server->public_logs . "../paintings/$gallery/$a->md5.png";
            }
        }
        return $art;
    }
}
