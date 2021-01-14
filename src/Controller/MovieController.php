<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Manager\CommentManager;
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
    private $commentManager;

    public function __construct(HttpClientInterface $httpClient, TMDBService $TMDBService, CommentManager $commentManager)
    {
        $this->client = $httpClient;
        $this->TMDBService = $TMDBService;
        $this->commentManager = $commentManager;
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

        return $this->render('movie/_search_result.html.twig', [
            'movies' => $movies
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

        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['tmdbId'=>$tmdbId]);

        $currentUserComment = $this->commentManager->getCommentFrom(['author'=>$this->getUser(), 'tmdbId'=>$tmdbId]);
        !$currentUserComment instanceof Comment ? $currentUserComment = new Comment() : null;
        $form = $this->createForm(CommentType::class, $currentUserComment, ['tmdbId' => $tmdbId]);

        return $this->render('movie/details.html.twig', [
            'movie' => $movie,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}
