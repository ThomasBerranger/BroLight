<?php

namespace App\EventListener;

use App\Entity\Avatar;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postLoad(User $user): void
    {
    }

    public function prePersist(User $user): void
    {
        $user->setroles([User::ROLE_USER]);
        $user->setslug(strtolower($user->getFirstname().$user->getLastname()));
        $user->setusername(strtolower($user->getFirstname().$user->getLastname().time()));
        $user->setUpdatedAt(new \DateTime());
        $user->setcreatedAt(new \DateTime());

        $avatar = new Avatar();
        $avatar->setAuthor($user);
        $this->entityManager->persist($avatar);
    }

    public function preUpdate(User $user): void
    {
        $user->setUpdatedAt(new \DateTime());
        $user->setcreatedAt(new \DateTime());
    }
}