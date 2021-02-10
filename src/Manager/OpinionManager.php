<?php

namespace App\Manager;

use App\Entity\Opinion;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class OpinionManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findOrCreate(User $author, string $tmdbId): Opinion
    {
        $opinion = $this->entityManager->getRepository(Opinion::class)->findOneBy(['author' => $author, 'tmdbId' => $tmdbId]);

        if (!$opinion instanceof Opinion) {
            $opinion = new Opinion();
            $opinion->setAuthor($author);
            $opinion->setTmdbId($tmdbId);
        }

        return $opinion;
    }

    public function findFollowingsOpinions(User $user): array
    {
        return $this->entityManager->getRepository(Opinion::class)->findFollowingsViews($user);
    }

    public function createView(User $author, int $tmdbId): Opinion
    {
        $opinion = $this->findOrCreate($author, $tmdbId);

        $opinion->setIsViewed(true);

        return $this->save($opinion);
    }

    public function deleteView(User $author, int $tmdbId): Opinion
    {
        $opinion = $this->findOrCreate($author, $tmdbId);

        $opinion->setIsViewed(false);

        return $this->save($opinion);
    }

    public function delete(Opinion $opinion): void
    {
        $this->entityManager->remove($opinion);
        $this->entityManager->flush();
    }

    public function save(Opinion $opinion): Opinion
    {
        $this->entityManager->persist($opinion);
        $this->entityManager->flush();

        return $opinion;
    }
}