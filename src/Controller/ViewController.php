<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\View;
use App\Manager\MovieManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view", name="view.")
 */
class ViewController extends AbstractController
{
    private $entityManager;
    private $movieManager;

    public function __construct(EntityManagerInterface $entityManager, MovieManager $movieManager)
    {
        $this->entityManager = $entityManager;
        $this->movieManager = $movieManager;
    }

    /**
     * @Route("/create/{tmdbId}", name="create")
     *
     * @param int $tmdbId
     *
     * @return JsonResponse
     */
    public function createView(int $tmdbId): JsonResponse
    {
        $view = new View();

        $view->setAuthor($this->getUser());
        $view->setTmdbId($tmdbId);

        $this->entityManager->persist($view);
        $this->entityManager->flush();

        return $this->json($view, 202);
    }
}
