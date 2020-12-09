<?php

namespace App\Manager;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findOrCreate(int $tmdbMovieId): Movie
    {
        $movie = $this->entityManager->getRepository(Movie::class)->findOneBy(['tmdbMovieId' => $tmdbMovieId]);

        if (!$movie) {
            $movie = new Movie();
            $movie->setTmdbMovieId($tmdbMovieId);

            $this->entityManager->persist($movie);
            $this->entityManager->flush();
        }

        return $movie;
    }
}