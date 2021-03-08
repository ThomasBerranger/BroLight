<?php

namespace App\Manager;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;

class GenreManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findSelectedIds(array $ids): array
    {
        return $this->entityManager->getRepository(Genre::class)->findIfExists($ids);
    }
}