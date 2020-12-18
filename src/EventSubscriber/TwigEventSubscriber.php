<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\UserRelationshipRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $userRelationshipRepository;
    private $security;

    public function __construct(Environment $twig, UserRelationshipRepository $userRelationshipRepository, Security $security)
    {
        $this->twig = $twig;
        $this->userRelationshipRepository = $userRelationshipRepository;
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }

    public function onControllerEvent()
    {
        $currentUser = $this->security->getUser();

        if($currentUser instanceof User) {
            $this->twig->addGlobal('notifications', count($this->userRelationshipRepository->findPendingFollowFor($currentUser)));
        }
    }
}
