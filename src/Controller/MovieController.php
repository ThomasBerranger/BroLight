<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Manager\OpinionManager;
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
    private OpinionManager $opinionManager;

    public function __construct(HttpClientInterface $httpClient, MovieService $movieService, TMDBService $TMDBService, GenreService $genreService, OpinionManager $opinionManager)
    {
        $this->client = $httpClient;
        $this->movieService = $movieService;
        $this->TMDBService = $TMDBService;
        $this->genreService = $genreService;
        $this->opinionManager = $opinionManager;
    }

    /**
     * @Route("/trending", name="trending")
     */
    public function trending(): Response
    {
        $trendingMovies = $this->movieService->getTrendingMovies();

        $opinionsNumberByTrendingMovies = $this->opinionManager->findFollowingsOpinionNumber($this->getUser(), array_column($trendingMovies, 'id'));

        $genres = $this->genreService->findAllTMDBIdAndName();

        return $this->render('movie/trending.html.twig', [
            'trendingMovies' => $trendingMovies,
            'genres' => array_column($genres, 'name', 'tmdbId'),
            'opinionsNumberByTrendingMovies' => array_column($opinionsNumberByTrendingMovies, 'opinionsNumber', 'tmdbId')
        ]);
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

        $opinions = $this->opinionManager->findAllFollowingsOpinionFor($this->getUser(), $tmdbId);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
            'opinions' => $opinions,
        ]);
    }
}
