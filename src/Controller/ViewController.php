<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\View;
use App\Manager\ViewManager;
use App\Service\ViewService;
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
    private $viewManager;
    private $viewService;

    public function __construct(EntityManagerInterface $entityManager,ViewManager $viewManager, ViewService $viewService)
    {
        $this->entityManager = $entityManager;
        $this->viewManager = $viewManager;
        $this->viewService = $viewService;
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
            $view = $this->viewManager->createView($tmdbId);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }

        return $this->json($view, 201, [], ['groups' => 'view:read']);
    }

    /**
     * @Route("/delete/{tmdbId}", name="delete")
     *
     * @param int $tmdbId
     *
     * @return JsonResponse
     */
    public function deleteView(int $tmdbId): JsonResponse
    {
        try {
            $this->viewManager->deleteView($tmdbId);

            return $this->json(null);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }
}
