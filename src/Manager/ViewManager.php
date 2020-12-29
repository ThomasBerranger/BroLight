<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\View;
use App\Service\TMDBService;
use Doctrine\ORM\EntityManagerInterface;

class ViewManager
{
    private $entityManager;
    private $commentManager;
    private $tmdbService;

    public function __construct(EntityManagerInterface $entityManager, CommentManager $commentManager, TMDBService $tmdbService)
    {
        $this->entityManager = $entityManager;
        $this->commentManager = $commentManager;
        $this->tmdbService = $tmdbService;
    }

    public function getFollowingsViews(User $user): array
    {
        $followingsViews = $this->entityManager->getRepository(View::class)->findFollowingsViews($user);

        foreach ($followingsViews as $followingsView) {
            $followingsView->setMovie($this->tmdbService->getMovieById($followingsView->getTmdbId()));
            $followingsView->setAssociatedComment($this->commentManager->getAssociatedComment($followingsView));
        }

        return $followingsViews;
    }
}