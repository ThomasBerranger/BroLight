<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\UserRelationship;
use App\Manager\UserRelationshipManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/follow/{id}", name="follow")
     *
     * @param User $user
     * @param UserRelationshipManager $userRelationshipManager
     *
     * @return Response
     *
     * @throws Exception
     */
    public function follow(User $user, UserRelationshipManager $userRelationshipManager): Response
    {
        $userRelationshipManager->createFollowRelationship($this->getUser(), $user);

        return $this->json(null);
    }

    /**
     * @Route("/accept_follow/{id}", name="accept_follow")
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
        $userRelationshipManager->acceptFollowRelationship($user, $this->getUser());

        return $this->json(null);
    }
}
