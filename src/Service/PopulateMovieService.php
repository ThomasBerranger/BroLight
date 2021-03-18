<?php

namespace App\Service;

use App\Entity\Opinion;
use App\Entity\Wish;

class PopulateMovieService
{
    private TMDBService $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function movieHydrate($object)
    {
        if ($object instanceof Opinion or $object instanceof Wish) {
            $this->populate($object);
        } elseif (is_array($object)) {
            $objects = $object;
            foreach ($objects as $object) {
                $this->populate($object);
            }
        }
    }

    private function populate($object)
    {
        $object->setMovie($this->tmdbService->getMovieById($object->getTmdbId()));
    }
}