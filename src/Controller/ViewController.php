<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\View;
use App\Manager\MovieManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view", name="view.")
 */
class ViewController extends AbstractController
{
    private $entityManager;
    private $movieManager;

    public function __construct(EntityManagerInterface $entityManager, MovieManager $movieManager)
    {
        $this->entityManager = $entityManager;
        $this->movieManager = $movieManager;
    }

    /**
     * @Route("/create/{tmdbMovieId}", name="create")
     *
     * @param int $tmdbMovieId
     *
     * @return Response
     */
    public function createView(int $tmdbMovieId): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $movie = $this->movieManager->findOrCreate($tmdbMovieId);

        $view = new View();
        $view->setAuthor($currentUser);
        $view->setMovie($movie);

        $this->entityManager->persist($view);
        $this->entityManager->flush();

        return $this->redirectToRoute('movie.list');
    }
}
