<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Relationship;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Relationship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relationship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relationship[]    findAll()
 * @method Relationship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelationshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Relationship::class);
    }

    public function findPendingFollowersOf(User $user): array
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

    public function findAcceptedRelationshipsOf(User $user, ?DateTime $dateLimit): array
    {
        return $this->createQueryBuilder('ur')
            ->where('ur.userTarget = :userId')
            ->andWhere('ur.status = :status')
            ->andWhere('ur.createdAt > :dateLimit')
            ->setParameters([
                'userId' => $user->getId(),
                'status' => Relationship::STATUS['ACCEPTED_FOLLOW_REQUEST'],
                'dateLimit' => $dateLimit ? $dateLimit : 0
            ])
            ->getQuery()
            ->getResult()
            ;
    }
}
