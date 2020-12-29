<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserRelationship;
use App\Entity\View;
use App\Manager\UserRelationshipManager;
use App\Manager\ViewManager;

class UserService
{
    private $viewManager;

    public function __construct(ViewManager $viewManager)
    {
        $this->viewManager = $viewManager;
    }

    public function getTimeline(User $user): array
    {
        $timeline = [];

        $views = $this->viewManager->getFollowingsViews($user);

        $timeline = $views;

        return $timeline;
    }
}