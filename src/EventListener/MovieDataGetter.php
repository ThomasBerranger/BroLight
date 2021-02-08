<?php

namespace App\EventListener;

use App\Entity\View;
use App\Service\TMDBService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieDataGetter
{
    private $TMDBService;

    public function __construct(TMDBService $TMDBService)
    {
        $this->TMDBService = $TMDBService;
    }

    public function postLoad(View $view, LifecycleEventArgs $event): void
    {
        $view->setMovie($this->TMDBService->getMovieById($view->getTmdbId()));
    }
}