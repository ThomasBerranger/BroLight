<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Rate;
use App\Entity\View;
use App\Manager\CommentManager;
use App\Manager\ViewManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class EntityLinkerService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAndLinkView($object): bool
    {
        if ($object instanceof Comment || $object instanceof Rate) {
            $view = $this->entityManager->getRepository(View::class)->findOneBy(['author'=>$object->getAuthor(), 'tmdbId'=>$object->getTmdbId()]);

            if ($view instanceof View) {
                $object->setView($view);

                return true;
            }
        }

        return false;
    }

    public function findAndLinkCommentIfExist(View $view): void
    {
        if (!$view->getComment() instanceof Comment) {
            $comment = $this->entityManager->getRepository(Comment::class)->findOneBy(['author'=>$view->getAuthor(), 'tmdbId'=>$view->getTmdbId()]);

            if ($comment instanceof Comment) {
                $comment->setView($view);

                $this->entityManager->persist($comment);
                $this->entityManager->flush();
            }
        }
    }
}