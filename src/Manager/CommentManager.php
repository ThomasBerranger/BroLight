<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\View;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAssociatedComment(View $view)
    {
        return $this->entityManager->getRepository(Comment::class)->findAssociatedComment($view);
    }
}