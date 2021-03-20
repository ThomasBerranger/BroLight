<?php

namespace App\EventListener;

use App\Entity\Opinion;
use App\Service\TMDBService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class OpinionListener
{
    private EntityManagerInterface $entityManager;
    private TMDBService $TMDBService;

    public function __construct(TMDBService $TMDBService, EntityManagerInterface $entityManager)
    {
        $this->TMDBService = $TMDBService;
        $this->entityManager = $entityManager;
    }

    public function prePersist(Opinion $opinion, LifecycleEventArgs $event): void
    {
        $this->updateViewedAt($opinion, $event);
        $this->updateCommentedAt($opinion, $event);
    }

    public function preUpdate(Opinion $opinion, LifecycleEventArgs $event): void
    {
        $this->updateViewedAt($opinion, $event);
        $this->updateCommentedAt($opinion, $event);
    }

    private function updateViewedAt(Opinion $opinion, LifecycleEventArgs $event): void
    {
        if (!$opinion->getIsViewed()) {
            $opinion->setViewedAt(null);
        } else if (!property_exists($event, 'entityChangeSet')) {
            $opinion->setViewedAt(new \DateTime());
        } else if (property_exists($event, 'entityChangeSet') and array_key_exists('isViewed', $event->getEntityChangeSet())) {
            $opinion->setViewedAt(new \DateTime());
        }
    }

    private function updateCommentedAt(Opinion $opinion, LifecycleEventArgs $event): void
    {
        if (!$opinion->getComment() or $opinion->getComment() == '') {
            $opinion->setComment(null);
            $opinion->setCommentedAt(null);
        } else if (!property_exists($event, 'entityChangeSet')) {
            $opinion->setCommentedAt(new \DateTime());
        } else if (property_exists($event, 'entityChangeSet') and array_key_exists('comment', $event->getEntityChangeSet())) {
            $opinion->setCommentedAt(new \DateTime());
        }
    }
}