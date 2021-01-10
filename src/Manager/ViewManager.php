<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\View;
use App\Service\TMDBService;
use App\Service\ViewService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ViewManager
{
    private $entityManager;
    private $security;
    private $validator;
    private $commentManager;
    private $tmdbService;
    private $viewService;

    public function __construct(EntityManagerInterface $entityManager, Security $security, ValidatorInterface $validator, CommentManager $commentManager, TMDBService $tmdbService, ViewService $viewService)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->validator = $validator;
        $this->commentManager = $commentManager;
        $this->tmdbService = $tmdbService;
        $this->viewService = $viewService;
    }

    public function createView(int $tmdbId)
    {
        $view = new View();

        $view->setAuthor($this->security->getUser());
        $view->setTmdbId($tmdbId);

        $errors = $this->validator->validate($view);
        if (count($errors) > 0) {
            throw new Exception((string) $errors);
        }

        $this->entityManager->persist($view);
        $this->entityManager->flush();

        $this->viewService->associateComment($view);

        return $view;
    }

    public function createViewFromCommentIfNotExist(Comment $comment): View
    {
        $view = $this->entityManager->getRepository(View::class)->findOneBy(['author'=>$comment->getAuthor(), 'tmdbId'=>$comment->getTmdbId()]);

        if (!$view instanceof View) {
            $view = new View();

            $view->setAuthor($comment->getAuthor());
            $view->setTmdbId($comment->getTmdbId());
        }

        $view->setComment($comment);

        $this->entityManager->persist($view);

        return $view;
    }

    public function getFollowingsViews(User $user): array
    {
        $followingsViews = $this->entityManager->getRepository(View::class)->findFollowingsViews($user);

        foreach ($followingsViews as $followingsView) {
            $followingsView->setMovie($this->tmdbService->getMovieById($followingsView->getTmdbId()));
        }

        return $followingsViews;
    }
}