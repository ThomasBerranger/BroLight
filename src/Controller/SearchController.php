<?php

namespace App\Controller;

use App\Manager\UserManager;
use App\Service\TMDBService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search", name="search.")
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
     * @Route("", name="index")
     */
    public function search(): Response
    {
        return $this->render('search/index.html.twig');
    }

    /**
     * @Route("/search_result/{text}", name="result")
     *
     * @param string $text
     *
     * @return Response
     */
    public function search_result(string $text): Response
    {
        return $this->render('search/_result.html.twig', [
            'users' => $this->userManager->findByName($text),
            'movies' => $this->TMDBService->getSearchedMovies($text)
        ]);
    }
}
