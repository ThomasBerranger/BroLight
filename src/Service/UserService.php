<?php

namespace App\Service;

use App\Entity\Podium;
use App\Entity\User;
use App\Manager\OpinionManager;
use App\Manager\RelationshipManager;
use Symfony\Component\Security\Core\Security;

class UserService
{
    private Security $security;
    private TMDBService $TMDBService;
    private PopulateMovieService $populateMovieService;
    private OpinionManager $opinionManager;
    private RelationshipManager $relationshipManager;

    public function __construct(Security $security, TMDBService $TMDBService, PopulateMovieService $populateMovieService, OpinionManager $opinionManager, RelationshipManager $relationshipManager)
    {
        $this->security = $security;
        $this->TMDBService = $TMDBService;
        $this->populateMovieService = $populateMovieService;
        $this->opinionManager = $opinionManager;
        $this->relationshipManager = $relationshipManager;
    }

    public function getTimelineEvents(User $user, int $offset, int $limit): array
    {
        $limit += 1; // dépasser la limite de 1 pour encadrer les autres events

        $followingsOpinions = $this->opinionManager->findFollowingsOpinions($user, $offset, $limit);

        if(!$followingsOpinions)
            return [];

        if (count($followingsOpinions) != $limit) { // last call
            $olderOpinion = $followingsOpinions[0];
            $acceptedRelationships = $this->relationshipManager->findAcceptedRelationshipsOfBetween($user, null, $olderOpinion ? $olderOpinion->getUpdatedAt() : null);
        } elseif ($offset === 0) { // first call
            $youngestOpinion = $followingsOpinions[count($followingsOpinions)-1];
            $acceptedRelationships = $this->relationshipManager->findAcceptedRelationshipsOfBetween($user, $youngestOpinion ? $youngestOpinion->getUpdatedAt() : null, null);
            unset($followingsOpinions[count($followingsOpinions)-1]); // remove last opinion
        } else { // normal call
            $youngestOpinion = $followingsOpinions[count($followingsOpinions)-1];
            $olderOpinion = $followingsOpinions[0];
            $acceptedRelationships = $this->relationshipManager->findAcceptedRelationshipsOfBetween($user, $youngestOpinion ? $youngestOpinion->getUpdatedAt() : null, $olderOpinion ? $olderOpinion->getUpdatedAt() : null);
            unset($followingsOpinions[count($followingsOpinions)-1]); // remove last opinion
        }

        $this->populateMovieService->movieHydrate($followingsOpinions);

        $timelineEvents = array_merge($followingsOpinions, $acceptedRelationships);

        usort($timelineEvents, function ($object1, $object2) {
            return $object1->getUpdatedAt() < $object2->getUpdatedAt();
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