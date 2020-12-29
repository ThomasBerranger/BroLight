<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\View;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param View $view
     *
     * @return Comment Returns an array of Comment objects
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAssociatedComment(View $view)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.author = :author')
            ->andWhere('c.tmdbId = :tmdbId')
            ->setParameters([
                'author' => $view->getAuthor(),
                'tmdbId' => $view->getTmdbId()
            ])
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
