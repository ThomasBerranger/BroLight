<?php

namespace App\Manager;

use App\Entity\Podium;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class PodiumManager
{
    private EntityManagerInterface $entityManager;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function create(User $user, int $rank, int $tmdbId): Podium
    {
        $podium = new Podium();

        $podium->setAuthor($user);
        $podium->assignedTmdbIdToRank($rank, $tmdbId);

        $this->entityManager->persist($podium);
        $this->entityManager->flush();

        return $podium;
    }

    public function update($podium, $rank, $tmdbId): Podium
    {
        $this->authorizationChecker->isGranted('update', $podium);

        $podium->assignedTmdbIdToRank($rank, $tmdbId);

        $this->entityManager->persist($podium);
        $this->entityManager->flush();

        return $podium;
    }
}