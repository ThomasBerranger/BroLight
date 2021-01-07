<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\View;
use Doctrine\ORM\EntityManagerInterface;

class ViewService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createViewFromComment(Comment $comment): View
    {
        $view = new View();

        $view->setComment($comment);
        $view->setAuthor($comment->getAuthor());
        $view->setTmdbId($comment->getTmdbId());

        $this->entityManager->persist($view);
        $this->entityManager->flush();

        return $view;
    }

    public function associateComment(View $view): ?View
    {
        /** @var View $view */
        $comment = $this->entityManager->getRepository(View::class)->findOneBy([
            'author' => $view->getAuthor(),
            'tmdbId' => $view->getTmdbId()
        ]);

        if ($comment instanceof Comment) {
            $view->setComment($comment);

            $this->entityManager->persist($view);
            $this->entityManager->flush();

            return $view;
        }

        return null;
    }
}