<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CommentManager
{
    private $entityManager;
    private $authorizationChecker;

    public function __construct(EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getCommentFrom(array $criteria): ?Comment
    {
        return $this->entityManager->getRepository(Comment::class)->findOneBy($criteria);
    }

    public function delete(Comment $comment): void
    {
        $this->authorizationChecker->isGranted('delete', $comment);

        $associatedView = $comment->getView();
        if ($associatedView instanceof View) {
            $this->authorizationChecker->isGranted('update', $associatedView);
            $associatedView->setComment(null);
            $this->entityManager->persist($associatedView);
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}