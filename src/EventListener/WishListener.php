<?php

namespace App\EventListener;

use App\Entity\Wish;
use App\Service\TMDBService;
use Doctrine\ORM\EntityManagerInterface;

class WishListener
{
    private EntityManagerInterface $entityManager;
    private TMDBService $TMDBService;

    public function __construct(TMDBService $TMDBService, EntityManagerInterface $entityManager)
    {
        $this->TMDBService = $TMDBService;
        $this->entityManager = $entityManager;
    }

    public function postLoad(Wish $wish): void
    {
        $wish->setMovie($this->TMDBService->getMovieById($wish->getTmdbId()));
    }
}