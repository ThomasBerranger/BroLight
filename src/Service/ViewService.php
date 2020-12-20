<?php

namespace App\Service;

use App\Entity\View;

class ViewService
{
    private $TMDBService;

    public function __construct(TMDBService $TMDBService)
    {
        $this->TMDBService = $TMDBService;
    }

    public function getMovieData(View $view): View
    {
        $movieData = $this->TMDBService->getMovieById($view->getTmdbId());

        $view->setMovie($movieData);

        return $view;
    }
}