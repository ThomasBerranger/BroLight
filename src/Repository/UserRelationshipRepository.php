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

//    /**
//     * @return UserRelationship[] Returns an array of UserRelationship objects
//     */
//    public function findFollowers($user, $status): array
//    {
//        return $this->createQueryBuilder('ur')
//            ->andWhere('ur.userTarget = :userId')
//            ->setParameter('userId', $user->getId())
//            ->andWhere('ur.status = :status')
//            ->setParameter('status', $status)
//            ->orderBy('ur.id', 'ASC')
//            ->getQuery()
//            ->getResult()
//            ;
//    }
}
