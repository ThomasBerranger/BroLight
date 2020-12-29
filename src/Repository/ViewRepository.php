<?php

namespace App\Repository;

use App\Entity\UserRelationship;
use App\Entity\View;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method View|null find($id, $lockMode = null, $lockVersion = null)
 * @method View|null findOneBy(array $criteria, array $orderBy = null)
 * @method View[]    findAll()
 * @method View[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, View::class);
    }

    public function findFollowingsViews(User $user)
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.author', 'u', 'WITH', 'v.author = u.id')
            ->innerJoin('u.userRelationsAsTarget', 'ur', 'WITH', 'u.id = ur.userTarget')
            ->where('ur.status = :status')
            ->andWhere('ur.userSource = :userId')
            ->setParameters([
                'status' => UserRelationship::STATUS['ACCEPTED_FOLLOW_REQUEST'],
                'userId' => $user->getId()
            ])
            ->orderBy('v.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
