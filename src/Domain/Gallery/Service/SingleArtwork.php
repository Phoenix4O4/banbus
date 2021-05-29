<?php

namespace App\Domain\Gallery\Service;

use App\Domain\Gallery\Service\GalleryService;

class SingleArtwork extends GalleryService
{
    public function viewSingleArtwork(string $server, string $md5, bool $cast_vote = false)
    {
        var_dump($cast_vote);
        if (!$this->isValidServer($server)) {
            return $this->payload->throwError(401, "Invalid server selected");
        }
        if (!$art = $this->getArtForServer($md5)) {
            return $this->payload->throwError(401, "This artwork could not be located");
        }
        $rating = $this->repo->getRatingForArtwork($md5)->getResults();
        $art->title = (new \App\Utilities\HTMLFactory())->sanitizeString($art->title);
        $art->rating = (float) $rating->rating;
        $art->votes = $rating->votes;
        $art->url = $this->server->public_logs . "../paintings/$art->gallery/$art->md5.png";
        $art->server = $this->server;
        $this->payload->setTemplate('gallery/single.twig');
        return $this->payload->addData('art', $art);
    }
}
