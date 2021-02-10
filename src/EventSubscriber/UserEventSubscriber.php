<?php

namespace App\EventSubscriber;

use App\Event\UserEvent;
use App\Manager\AvatarManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEventSubscriber implements EventSubscriberInterface
{
    private AvatarManager $avatarManager;

    public function __construct(AvatarManager $avatarManager)
    {
        $this->avatarManager = $avatarManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::USER_CREATE_EVENT => 'onUserCreate'
        ];
    }

    public function onUserCreate(UserEvent $event): void
    {
        $this->avatarManager->createAvatarFor($event->getUser());
    }
}