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

    public function createRateFromView(View $view, int $rateValue): Rate
    {
        $rate = new Rate();

        $rate->setView($view);
        $rate->setAuthor($this->security->getUser());
        $rate->setTmdbId($view->getTmdbId());
        $rate->setRate($rateValue);

        $this->entityManager->persist($rate);

        return $rate;
    }
}