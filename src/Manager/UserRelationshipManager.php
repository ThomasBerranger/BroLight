<?php

namespace App\Manager;

use App\Entity\UserRelationship;
use Doctrine\ORM\EntityManagerInterface;

class UserRelationshipManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUserRelationship($follower, $following): UserRelationship
    {
        $userRelationship = new UserRelationship();

        $userRelationship->setFollower($follower);
        $userRelationship->setFollowing($following);

        $this->entityManager->persist($userRelationship);
        $this->entityManager->flush();

        return $userRelationship;
    }
}