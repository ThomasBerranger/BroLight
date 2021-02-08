<?php

namespace App\Service;

use App\Entity\Podium;
use App\Entity\User;
use App\Manager\ViewManager;

class UserService
{
    private $viewManager;
    private $TMDBService;

    public function __construct(ViewManager $viewManager, TMDBService $TMDBService)
    {
        $this->viewManager = $viewManager;
        $this->TMDBService = $TMDBService;
    }

    public function getTimeline(User $user): array
    {
        $timeline = [];

        $views = $this->viewManager->getFollowingsViews($user);

        $timeline = $views;

        return $timeline;
    }

    public function formattedPodium(User $user): void
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