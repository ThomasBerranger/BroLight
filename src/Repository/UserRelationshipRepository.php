<?php

namespace App\Repository;

use App\Entity\UserRelationship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserRelationship|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRelationship|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRelationship[]    findAll()
 * @method UserRelationship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRelationshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRelationship::class);
    }

    public function findPendingFollowFor($user): array
    {
        return $this->createQueryBuilder('ur')
            ->where('ur.userTarget = :userId')
            ->andWhere('ur.status = :status')
            ->setParameters([
                'userId' => $user->getId(),
                'status' => UserRelationship::STATUS['PENDING_FOLLOW_REQUEST']
            ])
            ->getQuery()
            ->getResult()
            ;
    }
}
