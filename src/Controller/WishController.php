<?php

namespace App\Controller;

use App\Manager\WishManager;
use App\Service\TMDBService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wish", name="wish.")
 */
class WishController extends AbstractController
{
    private WishManager $wishManager;
    private TMDBService $TMDBService;

    public function __construct(WishManager $wishManager, TMDBService $TMDBService)
    {
        $this->wishManager = $wishManager;
        $this->TMDBService = $TMDBService;
    }

    /**
     * @Route("/add/{tmdbId}", name="add")
     *
     * @param string $tmdbId
     *
     * @return JsonResponse
     */
    public function add(string $tmdbId): JsonResponse
    {
        $wish = $this->wishManager->save($this->getUser(), $tmdbId);

        return new JsonResponse([
            'button' => $this->renderView('wish/_button.html.twig', ['movie' => $this->TMDBService->getMovieById($wish->getTmdbId())]),
            'tmdbId' => $wish->getTmdbId()
        ], 201);
    }

    /**
     * @Route("/delete/{tmdbId}", name="delete")
     *
     * @param string $tmdbId
     *
     * @return JsonResponse
     */
    public function delete(string $tmdbId): JsonResponse
    {
        $this->wishManager->delete($this->getUser(), $tmdbId);

        return new JsonResponse([
            'button' => $this->renderView('wish/_button.html.twig', ['movie' => $this->TMDBService->getMovieById($tmdbId)]),
            'tmdbId' => $tmdbId
        ], 200);
    }
}
