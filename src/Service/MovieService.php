<?php

namespace App\Service;

use App\Manager\GenreManager;

class MovieService
{
    private TMDBService $TMDBService;
    private GenreManager $genreManager;

    public function __construct(TMDBService $TMDBService, GenreManager $genreManager)
    {
        $this->TMDBService = $TMDBService;
        $this->genreManager = $genreManager;
    }

    public function getTrendingMovies(): array
    {
        return $this->TMDBService->getTrendingMovies();
    }
}