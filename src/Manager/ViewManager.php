<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\View;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ViewManager
{
    private $entityManager;
    private $security;
    private $validator;

    public function __construct( EntityManagerInterface $entityManager, Security $security, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->validator = $validator;
    }

    public function getFrom(array $criteria): ?View
    {
        return $this->entityManager->getRepository(View::class)->findOneBy($criteria);
    }

    public function create(int $tmdbId): View
    {
        $view = new View();

        $view->setAuthor($this->security->getUser());
        $view->setTmdbId($tmdbId);

        $errors = $this->validator->validate($view);
        if (count($errors) > 0)
            throw new Exception((string) $errors);

        $this->entityManager->persist($view);
        $this->entityManager->flush();

        return $view;
    }

    public function delete(int $tmdbId): void
    {
        $view = $this->entityManager->getRepository(View::class)->findOneBy([
            'author'=>$this->security->getUser(),
            'tmdbId'=>$tmdbId
        ]);

        if ($view instanceof View) {
            if (!$this->security->isGranted('delete', $view))
                throw new Exception('Cet utilisateur n\'a pas le droit de supprimer ce visionnage.');

            $this->entityManager->remove($view);
            $this->entityManager->flush();
        }
    }

    public function getFollowingsViews(User $user): array
    {
        return $this->entityManager->getRepository(View::class)->findFollowingsViews($user);
    }
}