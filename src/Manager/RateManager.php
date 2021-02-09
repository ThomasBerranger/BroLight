<?php

namespace App\Manager;

use App\Entity\Rate;
use App\Entity\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class RateManager
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getFrom(array $criteria): ?Rate
    {
        return $this->entityManager->getRepository(Rate::class)->findOneBy($criteria);
    }

    public function createRateIfViewExist(int $tmdbId, int $rateValue): ?Rate
    {
        $view = $this->entityManager->getRepository(View::class)->findOneBy(['author'=>$this->security->getUser(), 'tmdbId'=>$tmdbId]);

        if (!$view instanceof View)
            return null;

        $rate = new Rate();

        $rate->setView($view);
        $rate->setAuthor($this->security->getUser());
        $rate->setTmdbId($view->getTmdbId());
        $rate->setValue($rateValue);

        $this->entityManager->persist($rate);

        return $rate;
    }
}