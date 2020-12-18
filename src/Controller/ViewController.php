<?php

namespace App\Controller;

use App\Entity\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view", name="view.")
 */
class ViewController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
