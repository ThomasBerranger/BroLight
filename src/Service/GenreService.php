<?php

namespace App\Service;

use App\Manager\GenreManager;

class GenreService
{
    private GenreManager $genreManager;

    public function __construct(GenreManager $genreManager)
    {
        $this->genreManager = $genreManager;
    }

    public function findAllTMDBIdAndName(): array
    {
        return $this->genreManager->findAllTMDBIdAndName();
    }
}