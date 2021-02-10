<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Manager\RelationshipManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user_relationship", name="user_relationship.")
 */
class RelationshipController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private RelationshipManager $relationshipManager;

    public function __construct(EntityManagerInterface $entityManager, RelationshipManager $relationshipManager)
    {
        $this->entityManager = $entityManager;
        $this->relationshipManager = $relationshipManager;
    }

    /**
     * @Route("/follow/{id}", name="follow", methods={"GET"})
     *
     * @param User $user
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function follow(User $user): JsonResponse
    {
        try {
            $this->relationshipManager->createFollowRelationship($this->getUser(), $user);

            return new JsonResponse([
                'view' => $this->renderView('user/_partials/_followingButton.html.twig', ['user' => $user]),
                'userId' => $user->getId()
            ], 200);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }

    /**
     * @Route("/unfollow/{id}", name="unfollow", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     *
     * @throws Exception
     */
    public function unfollow(User $user): Response
    {
        try {
            $this->relationshipManager->deleteFollowRelationship($this->getUser(), $user);

            return new JsonResponse([
                'view' => $this->renderView('user/_partials/_followingButton.html.twig', ['user' => $user]),
                'userId' => $user->getId()
            ], 200);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }

    /**
     * @Route("/accept_follow/{id}", name="accept_follow", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     *
     * @throws Exception
     */
    public function acceptFollow(User $user): Response
    {
        try {
            $this->relationshipManager->acceptFollowRelationship($user, $this->getUser());

            return $this->json([
                'view' => $this->renderView('user/_partials/_followerButton.html.twig', ['user' => $user]),
                'userId' => $user->getId()
            ], 201);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }

    /**
     * @Route("/refuse/{id}", name="refuse", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     *
     * @throws Exception
     */
    public function refuse(User $user): Response
    {
        try {
            $this->relationshipManager->deleteFollowRelationship($user, $this->getUser());

            return $this->json([
                'view' => $this->renderView('user/_partials/_followerButton.html.twig', ['user' => $user]),
                'userId' => $user->getId()
            ], 201);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }
}
