<?php

namespace App\Domain\Gallery\Service;

use App\Domain\Gallery\Service\GalleryService;

class ArtworkVoteService extends GalleryService
{
    public function castVote($vote)
    {
        if (!isset($vote['rating'], $vote['md5'], $vote['server'])) {
            $this->payload->throwError(401, 'Unable to cast vote on this artwork');
            return $this->payload;
        }
        if (!filter_var(
            $vote['rating'],
            FILTER_VALIDATE_INT,
            [
            'options' => [
                'min' => 1,
                'max' => 5
            ]
        ]
        )) {
            $this->payload->throwError(401, 'This vote is invalid');
            return $this->payload;
        }
        if (!$this->isValidServer($vote['server'])) {
            $this->payload->throwError(401, 'This vote is invalid.');
            return $this->payload;
        }

        if (!$ckey = $this->session->get('user')->getCkey()) {
            $this->payload->throwError(403, 'You must be logged in to vote');
            return $this->payload;
        }

        $this->repo->castVote(
            $vote['server'],
            $vote['md5'],
            $vote['rating'],
            $ckey
        );
        $art = $this->getSingleArtwork($vote['server'], $vote['md5']);
        $this->payload->asJson();
        $this->payload->addData('art', $art);
        return $this->payload;
    }
}
