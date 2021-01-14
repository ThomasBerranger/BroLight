<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\View;
use App\Manager\CommentManager;
use Doctrine\ORM\EntityManagerInterface;

class ViewService
{
    private $entityManager;
    private $commentManager;

    public function __construct(EntityManagerInterface $entityManager, CommentManager $commentManager)
    {
        $this->entityManager = $entityManager;
        $this->commentManager = $commentManager;
    }

    public function associateComment(View $view): ?View
    {
        $comment = $this->commentManager->getCommentFrom([
            'author' => $view->getAuthor(),
            'tmdbId' => $view->getTmdbId()
        ]);

        if ($comment instanceof Comment) {
            $view->setComment($comment);

            return $view;
        }

        return $view;
    }
}