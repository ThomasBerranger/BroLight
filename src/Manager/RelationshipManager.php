<?php

namespace App\Manager;

use App\Entity\Relationship;
use App\Entity\User;
use DateTime;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RelationshipManager
{
    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security)
    {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @param User $userSource
     * @param User $userTarget
     * @param int  $status
     *
     * @return Relationship
     *
     * @throws Exception
     */
    public function createFollowRelationship(User $userSource, User $userTarget, int $status = Relationship::STATUS['PENDING_FOLLOW_REQUEST']): Relationship
    {
        $userRelationship = new Relationship();

        $userRelationship->setUserSource($userSource);
        $userRelationship->setUserTarget($userTarget);
        $userRelationship->setStatus($status);

        $errors = $this->validator->validate($userRelationship);

        if ($this->security->isGranted('edit', $userRelationship) and count($errors) <= 0) {
            $this->entityManager->persist($userRelationship);
            $this->entityManager->flush();
        }

        return $userRelationship;
    }

    /**
     * @param User $userSource
     * @param User $userTarget
     *
     * @return Relationship
     *
     * @throws Exception
     */
    public function acceptFollowRelationship(User $userSource, User $userTarget): Relationship
    {
        $userRelationship = $this->entityManager->getRepository(Relationship::class)->findOneBy([
            'userSource' => $userSource,
            'userTarget' => $userTarget,
            'status' => Relationship::STATUS['PENDING_FOLLOW_REQUEST']
        ]);

        $userRelationship->setStatus(Relationship::STATUS['ACCEPTED_FOLLOW_REQUEST']);

        $errors = $this->validator->validate($userRelationship);

        if ($this->security->isGranted('edit', $userRelationship) and count($errors) <= 0) {
            $this->entityManager->persist($userRelationship);
            $this->entityManager->flush();
        }

        return $userRelationship;
    }

    /**
     * @param User $userSource
     * @param User $userTarget
     *
     * @return Relationship
     *
     */
    public function deleteFollowRelationship(User $userSource, User $userTarget): Relationship
    {
        $userRelationship = $this->entityManager->getRepository(Relationship::class)->findOneBy([
            'userSource' => $userSource,
            'userTarget' => $userTarget,
        ]);

        if ($this->security->isGranted('delete', $userRelationship)) {
            $this->entityManager->remove($userRelationship);
            $this->entityManager->flush();
        }

        return $userRelationship;
    }

    public function findAcceptedRelationshipsOfBetween(User $userTarget, ?DateTime $dateMin, ?DateTime $dateMax): array
    {
        return $relationships = $this->entityManager->getRepository(Relationship::class)->findAcceptedRelationshipsOfBetween($userTarget, $dateMin, $dateMax);
    }
}