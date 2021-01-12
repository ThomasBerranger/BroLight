<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUserAndMovieComment(User $user, int $tmdbId): ?Comment
    {
        $comment = $this->entityManager->getRepository(Comment::class)->findOneBy([
            'author'=>$user,
            'tmdbId'=>$tmdbId
        ]);

        return $comment;
    }

    public function getCommentFrom(array $criteria): ?Comment
    {
        return $this->entityManager->getRepository(Comment::class)->findOneBy($criteria);
    }
}