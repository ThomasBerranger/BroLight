<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class UserRelationshipService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

}