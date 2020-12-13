<?php

namespace App\Manager;

use App\Entity\UserRelationship;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class UserRelationshipManager
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function createFollowRelationship($userSource, $userTarget, $status = UserRelationship::STATUS['PENDING_FOLLOW_REQUEST']): UserRelationship
    {
        $userRelationship = new UserRelationship();

        $userRelationship->setUserSource($userSource);
        $userRelationship->setUserTarget($userTarget);
        $userRelationship->setStatus($status);

        $this->entityManager->persist($userRelationship);
        $this->entityManager->flush();

        return $userRelationship;
    }
}