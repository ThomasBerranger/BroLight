<?php

namespace App\Repository;

use App\Entity\Podium;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Podium|null find($id, $lockMode = null, $lockVersion = null)
 * @method Podium|null findOneBy(array $criteria, array $orderBy = null)
 * @method Podium[]    findAll()
 * @method Podium[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PodiumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Podium::class);
    }

    // /**
    //  * @return Podium[] Returns an array of Podium objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Podium
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
