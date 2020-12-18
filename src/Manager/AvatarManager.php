<?php

namespace App\Manager;

use App\Entity\Avatar;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AvatarManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createAvatarFor(User $user): Avatar
    {
        $avatar = new Avatar();
        $avatar->setAuthor($user);

        $this->entityManager->persist($avatar);
        $this->entityManager->flush();

        return $avatar;
    }
}