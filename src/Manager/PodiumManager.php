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

    public function update(User $user, $rank, $tmdbId): Podium
    {
        if ($user->getPodium() instanceof Podium) {
            $podium = $user->getPodium();
        } else {
            $podium = new Podium();
            $podium->setAuthor($user);
        }

        if ($this->authorizationChecker->isGranted('edit', $podium)) {
            $podium->assignedTmdbIdToRank($rank, $tmdbId);

            $this->entityManager->persist($podium);
            $this->entityManager->flush();
        }

        return $podium;
    }
}