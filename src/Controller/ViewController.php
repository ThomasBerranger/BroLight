<?php

namespace App\Controller;

use App\Manager\ViewManager;
use App\Service\TMDBService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view", name="view.")
 */
class ViewController extends AbstractController
{
    private $viewManager;
    private $tmdbService;

    public function __construct(ViewManager $viewManager, TMDBService $tmdbService)
    {
        $this->viewManager = $viewManager;
        $this->tmdbService = $tmdbService;
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
