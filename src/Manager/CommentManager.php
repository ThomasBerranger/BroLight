<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}