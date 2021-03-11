<?php

namespace App\Service;

use App\Entity\Podium;
use App\Entity\User;
use App\Manager\OpinionManager;
use App\Manager\RelationshipManager;
use DateTime;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Date;

class UserService
{
    private Security $security;
    private TMDBService $TMDBService;
    private OpinionManager $opinionManager;
    private RelationshipManager $relationshipManager;

    public function __construct(Security $security, TMDBService $TMDBService, OpinionManager $opinionManager, RelationshipManager $relationshipManager)
    {
        $this->security = $security;
        $this->TMDBService = $TMDBService;
        $this->opinionManager = $opinionManager;
        $this->relationshipManager = $relationshipManager;
    }

    public function getTimelineEvents(User $user, int $offset = 0): array
    {
        $followingsOpinions = $this->opinionManager->findFollowingsOpinions($user, $offset);

//        $followingsOpinions ? $dateLimit = $followingsOpinions[count($followingsOpinions)-1]->getCreatedAt() : $dateLimit = null;
//        $acceptedRelationships = $this->relationshipManager->findAcceptedRelationship($user, $dateLimit);

        $timelineEvents = array_merge([], $followingsOpinions);

        usort($timelineEvents, function ($object1, $object2) {
            return $object1->getCreatedAt() < $object2->getCreatedAt();
        });

        return $timelineEvents;
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