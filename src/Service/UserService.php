<?php

namespace App\Service;

use App\Entity\Podium;
use App\Entity\User;
use App\Manager\OpinionManager;

class UserService
{
    private OpinionManager $opinionManager;
    private TMDBService $TMDBService;

    public function __construct(OpinionManager $opinionManager, TMDBService $TMDBService)
    {
        $this->opinionManager = $opinionManager;
        $this->TMDBService = $TMDBService;
    }

    public function getTimeline(User $user): array
    {
        return $this->opinionManager->findFollowingsOpinions($user);
    }

    public function formattedPodium(User $user): array
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

        return $formattedPodium;
    }
}