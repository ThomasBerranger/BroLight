<?php

namespace App\Controller;

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
    private $client;
    private $TMDBService;

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

        return $this->render('movie/trending.html.twig', [
            'trendingMovies' => $trendingMovies,
        ]);
    }

    /**
     * @Route("/list", name="list")
     */
    public function list(): Response
    {

        return $this->render('movie/list.html.twig');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(): Response
    {

        return $this->render('movie/search.html.twig');
    }

    /**
     * @Route("/details", name="details")
     */
    public function details(): Response
    {

        return $this->render('movie/details.html.twig');
    }
}
