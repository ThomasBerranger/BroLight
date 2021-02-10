<?php

namespace App\Controller;

use App\Entity\Podium;
use App\Entity\User;
use App\Manager\PodiumManager;
use App\Service\TMDBService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/podium", name="podium.")
 */
class PodiumController extends AbstractController
{
    private TMDBService $TMDBService;
    private PodiumManager $podiumManager;

    public function __construct(TMDBService $TMDBService, PodiumManager $podiumManager)
    {
        $this->TMDBService = $TMDBService;
        $this->podiumManager = $podiumManager;
    }

    /**
     * @Route("/form/{rank}", name="form")
     *
     * @param int $rank
     *
     * @return Response
     */
    public function form(int $rank): Response
    {
        return $this->render('podium/_form.html.twig', ['rank' => $rank]);
    }

    /**
     * @Route("/search/{rank}/{title}", name="search", methods={"GET"})
     *
     * @param int    $rank
     * @param string $title
     *
     * @return Response
     */
    public function search(int $rank, string $title): Response
    {
        $movies = $this->TMDBService->getSearchedMovies($title);

        return $this->render('podium/_search_result.html.twig', [
            'rank' => $rank,
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/vote/{rank}/{tmdbId}", name="vote", methods={"GET"})
     *
     * @param int $rank
     * @param int $tmdbId
     *
     * @return Response
     */
    public function vote(int $rank, int $tmdbId): Response
    {
        try {
            $this->podiumManager->update($this->getUser(), $rank, $tmdbId);

            return $this->json(null);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }
}
