<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserManager
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function save(User $user): User
    {
        if ($this->security->isGranted('edit', $user)) {
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
}