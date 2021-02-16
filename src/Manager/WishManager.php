<?php

namespace App\Manager;

use App\Entity\Podium;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WishManager
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private Security $security;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->security = $security;
    }

    public function save(User $user, string $tmdbId): Wish
    {
        $wish = new Wish();

        $wish->setAuthor($user);
        $wish->setTmdbId($tmdbId);

        $errors = $this->validator->validate($wish);

        if ($this->security->isGranted('edit', $wish) and count($errors) <= 0) {
            $this->entityManager->persist($wish);
            $this->entityManager->flush();
        }

        return $wish;
    }

    public function delete(User $user, string $tmdbId): void
    {
        $wish = $this->entityManager->getRepository(Wish::class)->findOneBy(['author' => $user, 'tmdbId' => $tmdbId]);

        if ($this->security->isGranted('delete', $wish)) {
            $this->entityManager->remove($wish);
            $this->entityManager->flush();
        }
    }
}