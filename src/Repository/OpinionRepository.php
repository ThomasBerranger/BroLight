<?php

namespace App\Repository;

use App\Entity\Opinion;
use App\Entity\Relationship;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findFollowingsOpinions(User $user)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.author', 'u', 'WITH', 'o.author = u.id')
            ->innerJoin('u.relationsAsTarget', 'ur', 'WITH', 'u.id = ur.userTarget')
            ->where('ur.status = :status AND ur.userSource = :userId')
            ->orWhere('o.author = :userId')
            ->setParameters([
                'status' => Relationship::STATUS['ACCEPTED_FOLLOW_REQUEST'],
                'userId' => $user->getId()
            ])
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
