<?php

namespace App\Manager;

use App\Entity\Avatar;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class AvatarManager
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function createAvatarFor(User $user): Avatar
    {
        $avatar = new Avatar();
        $avatar->setAuthor($user);

        $this->entityManager->persist($avatar);
        $this->entityManager->flush();

        return $avatar;
    }

    public function save(Avatar $avatar): Avatar
    {
        if ($this->security->isGranted('edit', $avatar)) {
            $this->entityManager->persist($avatar);
            $this->entityManager->flush();
        }

        return $avatar;
    }
}