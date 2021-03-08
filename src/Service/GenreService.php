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

    public function findAndFormatAll(): array
    {
        $genres = $this->genreManager->findAll();
        $result = [];

        foreach ($genres as $genre) {
            $result[$genre->getTmdbId()] = $genre->getName();
        }

        return $result;
    }
}