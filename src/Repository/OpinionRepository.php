<?php

namespace App\Repository;

use App\Entity\Opinion;
use App\Entity\Relationship;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method Opinion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opinion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opinion[]    findAll()
 * @method Opinion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpinionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opinion::class);
    }

    public function findFollowingsOpinions(User $user, int $offset, int $limit): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.author', 'u')
            ->innerJoin('u.relationsAsTarget', 'r')
            ->where('o.author = :userId OR r.status = :status AND r.userSource = :userId')
            ->setParameters([
                'status' => Relationship::STATUS['ACCEPTED_FOLLOW_REQUEST'],
                'userId' => $user->getId()
            ])
            ->groupBy('o')
            ->orderBy('o.updatedAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFollowingsOpinionNumber(User $user, array $tmdbIds): array
    {
        return $this->createQueryBuilder('o')
            ->select(['o.tmdbId', 'COUNT(DISTINCT o) as opinionsNumber'])
            ->innerJoin('o.author', 'u')
            ->innerJoin('u.relationsAsTarget', 'r')
            ->where('o.tmdbId IN (:tmdbIds) AND (o.author = :userId OR r.status = :status AND r.userSource = :userId)')
            ->setParameters([
                'tmdbIds' => $tmdbIds,
                'status' => Relationship::STATUS['ACCEPTED_FOLLOW_REQUEST'],
                'userId' => $user->getId()
            ])
            ->groupBy('o.tmdbId')
            ->getQuery()
            ->getResult()
            ;
    }
}
