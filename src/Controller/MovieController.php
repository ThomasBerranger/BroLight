<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Service\GenreService;
use App\Service\MovieService;
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
    private MovieService $movieService;
    private TMDBService $TMDBService;
    private GenreService $genreService;

    public function __construct(HttpClientInterface $httpClient, MovieService $movieService, TMDBService $TMDBService, GenreService $genreService)
    {
        $this->client = $httpClient;
        $this->movieService = $movieService;
        $this->TMDBService = $TMDBService;
        $this->genreService = $genreService;
    }

    /**
     * @Route("/trending", name="trending")
     */
    public function trending(): Response
    {
        $trendingMovies = $this->movieService->getTrendingMovies();

        $genres = $this->genreService->findAndFormatAll();

        return $this->render('movie/trending.html.twig', ['trendingMovies' => $trendingMovies, 'genres' => $genres]);
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
