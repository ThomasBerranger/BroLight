<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\View;
use App\Service\TMDBService;
use Doctrine\ORM\EntityManagerInterface;

class ViewManager
{
    private $entityManager;
    private $tmdbService;

    public function __construct(EntityManagerInterface $entityManager, TMDBService $tmdbService)
    {
        $this->entityManager = $entityManager;
        $this->tmdbService = $tmdbService;
    }

    public function getFollowingsViews(User $user, bool $isMovieData = true): array
    {
        $followingsViews = $this->entityManager->getRepository(View::class)->findFollowingsViews($user);

        /** @var View $followingsView */
        foreach ($followingsViews as $followingsView) {
            $followingsView->setMovie($this->tmdbService->getMovieById($followingsView->getId()));
        }

        return $followingsViews;
    }
}