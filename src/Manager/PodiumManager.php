<?php

namespace App\Manager;

use App\Entity\Podium;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PodiumManager
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
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

        $podium->assignedTmdbIdToRank($rank, $tmdbId);

        $errors = $this->validator->validate($podium);

        if ($this->authorizationChecker->isGranted('edit', $podium) and count($errors) <= 0) {
            $this->entityManager->persist($podium);
            $this->entityManager->flush();
        }

        return $podium;
    }
}