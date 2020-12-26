<?php

namespace App\Controller;

use App\Entity\View;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/view", name="view.")
 */
class ViewController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/create/{tmdbId}", name="create")
     *
     * @param int $tmdbId
     *
     * @return JsonResponse
     */
    public function createView(int $tmdbId): JsonResponse
    {
        try {
            $view = new View();

            $view->setAuthor($this->getUser());
            $view->setTmdbId($tmdbId);

            $this->entityManager->persist($view);
            $this->entityManager->flush();

            return $this->json($view, 201, [], ['groups' => 'view:read']);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }

    /**
     * @Route("/remove/{id}", name="remove")
     *
     * @param View $view
     *
     * @return JsonResponse
     */
    public function removeView(View $view): JsonResponse
    {
        try {
            $this->denyAccessUnlessGranted('remove', $view);

            $this->entityManager->remove($view);
            $this->entityManager->flush();

            return $this->json(null);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }
}
