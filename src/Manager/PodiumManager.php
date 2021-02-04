<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\Podium;
use App\Entity\User;
use App\Entity\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class PodiumManager
{
    private $entityManager;
    private $authorizationChecker;

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