<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserRelationship;
use App\Entity\View;
use App\Manager\UserRelationshipManager;

class UserService
{
    private $viewService;
    private $userRelationshipManager;

    public function __construct(ViewService $viewService, UserRelationshipManager $userRelationshipManager)
    {
        $this->viewService = $viewService;
        $this->userRelationshipManager = $userRelationshipManager;
    }

    public function getUserTimeline(User $user): array
    {
        $timeline = [];
        $views = [];

        foreach ($user->getViews() as $view) { // Current user views
            array_push($views, $view);
        }

        foreach ($user->getFollowings() as $follower) { // Current user friends views
            foreach ($follower->getViews() as $view) {
                array_push($views, $view);
            }
        }

        foreach ($views as $view) {
            $this->viewService->getMovieData($view);

            array_push($timeline, $view);
        }

        usort($timeline, function($a, $b) {
            return $a->getCreatedAt()->getTimestamp() < $b->getCreatedAt()->getTimestamp();
        });

        return $timeline;
    }
}