<?php

namespace App\Manager;

use App\Entity\Opinion;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OpinionManager
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
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

    public function findFollowingsOpinions(User $user, int $offset): array
    {
        return $this->entityManager->getRepository(Opinion::class)->findFollowingsOpinions($user, $offset);
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
        if ($this->security->isGranted('delete', $opinion)) {
            $this->entityManager->remove($opinion);
            $this->entityManager->flush();
        }
    }

    public function save(Opinion $opinion): Opinion
    {
        $errors = $this->validator->validate($opinion);

        if ($this->security->isGranted('edit', $opinion) and count($errors) <= 0) {
            $this->entityManager->persist($opinion);
            $this->entityManager->flush();
        }

        return $opinion;
    }
}