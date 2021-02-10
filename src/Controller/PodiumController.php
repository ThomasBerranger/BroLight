<?php

namespace App\Controller;

use App\Entity\Podium;
use App\Manager\PodiumManager;
use App\Service\TMDBService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/podium", name="podium.")
 */
class PodiumController extends AbstractController
{
    private TMDBService $tmdbService;
    private EntityManagerInterface $entityManager;
    private PodiumManager $podiumManager;

    public function __construct(TMDBService $tmdbService, EntityManagerInterface $entityManager, PodiumManager $podiumManager)
    {
        $this->tmdbService = $tmdbService;
        $this->entityManager = $entityManager;
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
        return $this->render('podium/_form.html.twig', [
            'rank' => $rank,
        ]);
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
        $movies = $this->tmdbService->getSearchedMovies($title);

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
            $podium = $this->getUser()->getPodium();

            if (!$podium instanceof Podium) {
                $this->podiumManager->create($this->getUser(), $rank, $tmdbId);
            } else {
                $this->podiumManager->update($podium, $rank, $tmdbId);
            }

            return $this->json(null);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }
}
