<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\View;
use App\Manager\ViewManager;
use App\Service\EntityLinkerService;
use App\Service\TMDBService;
use App\Service\ViewService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view", name="view.")
 */
class ViewController extends AbstractController
{
    private $entityManager;
    private $viewManager;
    private $viewService;
    private $tmdbService;
    private $entityLinkerService;

    public function __construct(EntityManagerInterface $entityManager,ViewManager $viewManager, ViewService $viewService, TMDBService $tmdbService, EntityLinkerService $entityLinkerService)
    {
        $this->entityManager = $entityManager;
        $this->viewManager = $viewManager;
        $this->viewService = $viewService;
        $this->tmdbService = $tmdbService;
        $this->entityLinkerService = $entityLinkerService;
    }

    /**
     * @Route("/create/{tmdbId}", name="create")
     *
     * @param int $tmdbId
     *
     * @return JsonResponse
     */
    public function create(int $tmdbId): JsonResponse
    {
        try {
            $this->viewManager->create($tmdbId);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }

        return new JsonResponse($this->renderView('movie/_viewButton.html.twig', ['movie' => $this->tmdbService->getMovieById($tmdbId)]), 201);
    }

    /**
     * @Route("/delete/{tmdbId}", name="delete")
     *
     * @param int $tmdbId
     *
     * @return JsonResponse
     */
    public function delete(int $tmdbId): JsonResponse
    {
        try {
            $this->viewManager->delete($tmdbId);

        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }

        return new JsonResponse($this->renderView('movie/_viewButton.html.twig', ['movie' => $this->tmdbService->getMovieById($tmdbId)]), 200);
    }
}
