<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param User $user
     *
     * @return user[] Returns an array of user objects
     */
    public function findAllExcept(User $user): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.id != :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string $text
     *
     * @return user[] Returns an array of user objects
     */
    public function findByName(string $text): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.slug LIKE :text')
            ->setParameter('text', '%'.$text.'%')
            ->getQuery()
            ->getResult()
            ;
    }
}
