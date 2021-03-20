<?php

namespace App\Repository;

use App\Entity\Opinion;
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
        return $this->createQueryBuilder('r')
            ->where('r.userTarget = :userId')
            ->andWhere('r.status = :status')
            ->setParameters([
                'userId' => $user->getId(),
                'status' => Relationship::STATUS['PENDING_FOLLOW_REQUEST']
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAcceptedRelationshipsOfBetween(User $user, ?DateTime $dateMin, ?DateTime $dateMax): array
    {
        $relations = $this->createQueryBuilder('r')
            ->where('r.userTarget = :userId')
            ->andWhere('r.status = :status')
            ->setParameters([
                'userId' => $user->getId(),
                'status' => Relationship::STATUS['ACCEPTED_FOLLOW_REQUEST'],
            ])
        ;

        $dateMin ? $relations->andWhere('r.updatedAt >= :dateMin')->setParameter('dateMin', $dateMin) : null;
        $dateMax ? $relations->andWhere('r.updatedAt < :dateMax')->setParameter('dateMax', $dateMax) : null;

        return $relations->getQuery()->getResult();
    }
}
