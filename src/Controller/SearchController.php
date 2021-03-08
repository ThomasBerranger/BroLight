<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use App\Service\MovieService;
use App\Service\TMDBService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/movie", name="movie.")
 */
class SearchController extends AbstractController
{
    private TMDBService $TMDBService;
    private UserManager $userManager;

    public function __construct(TMDBService $TMDBService, UserManager $userManager)
    {
        $this->TMDBService = $TMDBService;
        $this->userManager = $userManager;
    }

    /**
     * @Route("/search_result/{text}", name="search_result")
     *
     * @param string $text
     *
     * @return Response
     */
    public function search_result(string $text): Response
    {
        return $this->render('movie/_search_result.html.twig', [
            'users' => $this->userManager->findByName($text),
            'movies' => $this->TMDBService->getSearchedMovies($text)
        ]);
    }
}
