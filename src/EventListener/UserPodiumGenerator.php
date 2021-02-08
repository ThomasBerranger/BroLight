<?php

namespace App\EventListener;

use App\Entity\Podium;
use App\Entity\User;
use App\Service\TMDBService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserPodiumGenerator
{
    private $TMDBService;

    public function __construct(TMDBService $TMDBService)
    {
        $this->TMDBService = $TMDBService;
    }

    public function postLoad(User $user, LifecycleEventArgs $event): void
    {
        if ($user->getPodium() instanceof Podium) {
            $formattedPodium = [
                1 => $user->getPodium()->getFirstTmdbId() ? $this->TMDBService->getMovieById($user->getPodium()->getFirstTmdbId()) : null,
                2 => $user->getPodium()->getSecondTmdbId() ? $this->TMDBService->getMovieById($user->getPodium()->getSecondTmdbId()) : null,
                3 => $user->getPodium()->getThirdTmdbId() ? $this->TMDBService->getMovieById($user->getPodium()->getThirdTmdbId()) : null,
            ];
        } else {
            $formattedPodium = [];
        }

        $user->setFormattedPodium($formattedPodium);
    }
}