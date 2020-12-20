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
        $userRelationships = [];

        foreach ($user->getViews() as $view) {
            array_push($views, $view);
        }

        foreach ($user->getFollowings() as $follower) {
            foreach ($follower->getViews() as $view) {
                array_push($views, $view);
            }
        }

        foreach ($views as $view) {
            $this->viewService->getMovieData($view);

            array_push($timeline, $this->formatEventToTimeline($view));
        }

//        $userRelationships = $this->userRelationshipManager->getAllUserRelationships($user);
//
//        foreach ($userRelationships as $userRelationship) {
//            array_push($timeline, $this->formatEventToTimeline($userRelationship));
//        }

        usort($timeline, function($a, $b)
        {
            return $a['date']->getTimestamp() < $b['date']->getTimestamp();
        });

        return $timeline;
    }

    private function formatEventToTimeline($event): array
    {
        $formattedEvent = [];

        if ($event instanceof View) {
            $formattedEvent['type'] = 'view';
            $formattedEvent['poster_path'] = $event->getMovie()['poster_path'];
            $formattedEvent['backdrop_path'] = $event->getMovie()['backdrop_path'];
            $formattedEvent['title'] = $event->getMovie()['title'];
            $formattedEvent['overview'] = $event->getMovie()['overview'];
            $formattedEvent['date'] = $event->getCreatedAt();
            $formattedEvent['author'] = $event->getAuthor();
        } elseif ($event instanceof UserRelationship) {
            $formattedEvent['type'] = 'userRelationship';

            $formattedEvent['author'] = $event->getUserTarget();

            $formattedEvent['status'] = $event->getStatus();
            $formattedEvent['date'] = $event->getUpdatedAt();
        }


        return $formattedEvent;
    }
}