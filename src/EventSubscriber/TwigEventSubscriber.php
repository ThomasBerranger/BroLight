<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\RelationshipRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private Security $security;
    private RelationshipRepository $relationshipRepository;

    public function __construct(Environment $twig, Security $security, RelationshipRepository $relationshipRepository)
    {
        $this->twig = $twig;
        $this->security = $security;
        $this->relationshipRepository = $relationshipRepository;
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
            $this->twig->addGlobal('notifications', count($this->relationshipRepository->findPendingFollowFor($currentUser)));
        }
    }
}
