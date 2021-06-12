<?php

namespace App\Domain\Gallery\Service;

use App\Service\Service;
use App\Factory\SettingsFactory;
use GuzzleHttp\Client as Guzzle;
use App\Domain\Gallery\Repository\GalleryRatingRepository as Repo;
use App\Domain\Servers\Service\ServerService;
use Symfony\Component\HttpFoundation\Session\Session;

class GalleryService extends Service
{
    protected $guzzle;
    protected $repo;

    protected $servers = [];
    protected $server = null;
    protected $url = '';

    public function __construct(SettingsFactory $settings, Guzzle $guzzle, Repo $repo, ServerService $servers, protected Session $session)
    {
        parent::__construct($settings);
        $this->guzzle = $guzzle;
        $this->repo = $repo;
        $this->servers = $servers;
    }

    protected function isValidServer(string $server)
    {
        if ($this->server = $this->servers->getServerByName($server)) {
            return true;
        }
    }

    protected function getArtForServer(?string $md5 = null)
    {
        $this->url = str_replace('data/logs', 'data', $this->server->public_logs . 'paintings.json');
        $artwork = json_decode($this->guzzle->request('GET', $this->url)->getBody());
        if (!$md5) {
            return $artwork;
        } else {
            foreach ($artwork as $gallery => $art) {
                foreach ($art as $a) {
                    if ($md5 === $a->md5) {
                        $a->gallery = $gallery;
                        return $a;
                    }
                }
            }
        }
    }

    protected function getSingleArtwork(string $server, string $md5)
    {
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
        return $art;
    }
}
