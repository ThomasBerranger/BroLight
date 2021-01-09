<?php

namespace App\Controller;

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
