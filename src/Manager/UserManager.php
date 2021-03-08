<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager
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

    public function save(User $user): User
    {
        $errors = $this->validator->validate($user);

        if ($this->security->isGranted('edit', $user) and count($errors) <= 0) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $user;
    }

    public function delete(User $user): void
    {
        if ($this->security->isGranted('delete', $user)) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }

    public function findByName(string $text): array
    {
        return $this->entityManager->getRepository(User::class)->findByName(str_replace(' ', '', $text));
    }
}