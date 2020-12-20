<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\UserRelationship;
use App\Manager\UserRelationshipManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user_relationship", name="user_relationship.")
 */
class UserRelationshipController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/follow/{id}", name="follow", methods={"GET"})
     *
     * @param User $user
     * @param UserRelationshipManager $userRelationshipManager
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function follow(User $user, UserRelationshipManager $userRelationshipManager): JsonResponse
    {
        try {
            $userRelationshipManager->createFollowRelationship($this->getUser(), $user);

            return $this->json(null);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }

    /**
     * @Route("/accept_follow/{id}", name="accept_follow", methods={"GET"})
     *
     * @param User $user
     * @param UserRelationshipManager $userRelationshipManager
     *
     * @return Response
     *
     * @throws Exception
     */
    public function acceptFollow(User $user, UserRelationshipManager $userRelationshipManager): Response
    {
        try {
            $userRelationshipManager->acceptFollowRelationship($user, $this->getUser());

            return $this->json(null);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }
}
