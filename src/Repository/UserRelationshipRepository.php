<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Relationship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Relationship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relationship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relationship[]    findAll()
 * @method Relationship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRelationshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relationship::class);
    }

    public function findAllUserRelationships(User $user): array
    {
        return $this->createQueryBuilder('ur')
            ->where('ur.userSource = :userId')
            ->orWhere('ur.userTarget = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPendingFollowFor(User $user): array
    {
        return $this->createQueryBuilder('ur')
            ->where('ur.userTarget = :userId')
            ->andWhere('ur.status = :status')
            ->setParameters([
                'userId' => $user->getId(),
                'status' => Relationship::STATUS['PENDING_FOLLOW_REQUEST']
            ])
            ->getQuery()
            ->getResult()
            ;
    }
}
