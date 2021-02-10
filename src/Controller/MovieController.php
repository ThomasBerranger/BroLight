<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Service\TMDBService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/movie", name="movie.")
 */
class MovieController extends AbstractController
{
    private HttpClientInterface $client;
    private TMDBService $TMDBService;

    public function __construct(HttpClientInterface $httpClient, TMDBService $TMDBService)
    {
        $this->client = $httpClient;
        $this->TMDBService = $TMDBService;
    }

    /**
     * @Route("/trending", name="trending")
     */
    public function trending(): Response
    {
        $trendingMovies = $this->TMDBService->getTrendingMovies();

        return $this->render('movie/trending.html.twig', ['trendingMovies' => $trendingMovies,]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(): Response
    {
        return $this->render('movie/search.html.twig');
    }

    /**
     * @Route("/search_result/{title}", name="search_result")
     *
     * @param string $title
     *
     * @return Response
     */
    public function search_result(string $title): Response
    {
        $movies = $this->TMDBService->getSearchedMovies($title);

        return $this->render('movie/_search_result.html.twig', ['movies' => $movies]);
    }

    /**
     * @Route("/details/{tmdbId}", name="details")
     *
     * @param int $tmdbId
     *
     * @return Response
     */
    public function details(int $tmdbId): Response
    {
        $movie = $this->TMDBService->getMovieById($tmdbId);

        $opinions = $this->getDoctrine()->getRepository(Opinion::class)->findBy(['tmdbId'=>$tmdbId]);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
            'opinions' => $opinions,
        ]);
    }
}
