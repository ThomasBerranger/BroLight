<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\View;
use App\Service\EntityLinkerService;
use App\Service\TMDBService;
use App\Service\ViewService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ViewManager
{
    private $entityManager;
    private $security;
    private $validator;
    private $authorizationChecker;
    private $commentManager;
    private $tmdbService;
    private $entityLinkerService;
    private $viewService;

    public function __construct(
        EntityManagerInterface        $entityManager,
        Security                      $security,
        ValidatorInterface            $validator,
        AuthorizationCheckerInterface $authorizationChecker,
        CommentManager                $commentManager,
        TMDBService                   $tmdbService,
        EntityLinkerService           $entityLinkerService,
        ViewService                   $viewService)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->validator = $validator;
        $this->authorizationChecker = $authorizationChecker;
        $this->commentManager = $commentManager;
        $this->tmdbService = $tmdbService;
        $this->entityLinkerService = $entityLinkerService;
        $this->viewService = $viewService;
    }

    public function createView(int $tmdbId): View
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

        return $view;
    }

    public function deleteView(int $tmdbId): void
    {
        $view = $this->entityManager->getRepository(View::class)->findOneBy([
            'author'=>$this->security->getUser(),
            'tmdbId'=>$tmdbId
        ]);

        if ($view instanceof View) {
            if (!$this->authorizationChecker->isGranted('delete', $view))
                throw new Exception('Cet utilisateur n\'a pas le droit de supprimer ce visionnage.');

            $this->entityManager->remove($view);
            $this->entityManager->flush();
        }
    }

    public function getFollowingsViews(User $user): array
    {
        $followingsViews = $this->entityManager->getRepository(View::class)->findFollowingsViews($user);

        return $followingsViews;
    }
}