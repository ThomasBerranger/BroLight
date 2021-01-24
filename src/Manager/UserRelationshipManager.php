<?php

namespace App\Manager;

use App\Entity\User;
use Exception;
use App\Entity\UserRelationship;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRelationshipManager
{
    private $security;
    private $validator;
    private $entityManager;

    public function __construct(Security $security, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    public function getAllUserRelationships(User $user): array
    {
        return $this->entityManager->getRepository(UserRelationship::class)->findAllUserRelationships($user);
    }

    /**
     * @param User $userSource
     * @param User $userTarget
     * @param int  $status
     *
     * @return void
     *
     * @throws Exception
     */
    public function createFollowRelationship(User $userSource, User $userTarget, int $status = UserRelationship::STATUS['PENDING_FOLLOW_REQUEST']): void
    {
        if ($userSource === $userTarget) {
            throw new Exception("User can't have relation with himself");
        }

        $userRelationship = new UserRelationship();

        $userRelationship->setUserSource($userSource);
        $userRelationship->setUserTarget($userTarget);
        $userRelationship->setStatus($status);

        $errors = $this->validator->validate($userRelationship);
        if (count($errors) > 0) {
            throw new Exception((string) $errors);
        }

        $this->entityManager->persist($userRelationship);
        $this->entityManager->flush();
    }

    /**
     * @param User $userSource
     * @param User $userTarget
     *
     * @return void
     *
     * @throws Exception
     */
    public function acceptFollowRelationship(User $userSource, User $userTarget): void
    {
        if ($userSource === $userTarget) {
            throw new Exception("User can't have relation with himself");
        }

        $userRelationship = $this->entityManager->getRepository(UserRelationship::class)->findOneBy([
            'userSource' => $userSource,
            'userTarget' => $userTarget,
            'status' => UserRelationship::STATUS['PENDING_FOLLOW_REQUEST']
        ]);

        $userRelationship->setStatus(UserRelationship::STATUS['ACCEPTED_FOLLOW_REQUEST']);

        $errors = $this->validator->validate($userRelationship);
        if (count($errors) > 0) {
            throw new Exception((string) $errors);
        }

        $this->entityManager->persist($userRelationship);
        $this->entityManager->flush();
    }

    /**
     * @param User $userSource
     * @param User $userTarget
     *
     * @return void
     *
     * @throws Exception
     */
    public function deleteFollowRelationship(User $userSource, User $userTarget): void
    {
        if ($userSource === $userTarget) {
            throw new Exception("User can't have relation with himself");
        }

        $userRelationship = $this->entityManager->getRepository(UserRelationship::class)->findOneBy([
            'userSource' => $userSource,
            'userTarget' => $userTarget,
        ]);

        $this->entityManager->remove($userRelationship);
        $this->entityManager->flush();
    }
}