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
        $trendingMovies = $this->TMDBService->getTrendingMovies();

        foreach ($trendingMovies as $key => $trendingMovie) {
            $trendingMovies[$key]['genres'] = $this->getCorrespondingGenres($trendingMovie);
        }

        return $trendingMovies;
    }

    private function getCorrespondingGenres(array $movie): array
    {
        return $this->genreManager->findSelectedIds($movie['genre_ids']);
    }

}