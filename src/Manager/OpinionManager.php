<?php

namespace App\Manager;

use App\Entity\Opinion;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class OpinionManager
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
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

        if ($this->security->isGranted('edit', $opinion)) {
            $opinion->setIsViewed(true);
        }

        return $this->save($opinion);
    }

    public function deleteView(User $author, int $tmdbId): Opinion
    {
        $opinion = $this->findOrCreate($author, $tmdbId);

        if ($this->security->isGranted('edit', $opinion)) {
            $opinion->setIsViewed(false);
        }

        return $this->save($opinion);
    }

    public function delete(Opinion $opinion): void
    {
        if ($this->security->isGranted('delete', $opinion)) {
            $this->entityManager->remove($opinion);
            $this->entityManager->flush();
        }
    }

    public function save(Opinion $opinion): Opinion
    {
        if ($this->security->isGranted('edit', $opinion)) {
            $this->entityManager->persist($opinion);
            $this->entityManager->flush();
        }

        return $opinion;
    }
}