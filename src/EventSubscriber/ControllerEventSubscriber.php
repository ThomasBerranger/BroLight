<?php

namespace App\EventSubscriber;

use App\Entity\Relationship;
use App\Entity\User;
use App\Repository\RelationshipRepository;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class ControllerEventSubscriber implements EventSubscriberInterface
{
    private Environment $twig;
    private Security $security;
    private RelationshipRepository $relationshipRepository;
    private UserRepository $userRepository;

    public function __construct(Environment $twig, Security $security, RelationshipRepository $relationshipRepository, UserRepository $userRepository)
    {
        $this->twig = $twig;
        $this->security = $security;
        $this->relationshipRepository = $relationshipRepository;
        $this->userRepository = $userRepository;
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
            $this->twig->addGlobal('notifications', count($this->relationshipRepository->findPendingFollowersOf($currentUser)));
        }
    }
}
