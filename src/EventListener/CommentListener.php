<?php

namespace App\EventListener;

use App\Entity\Comment;
use App\Manager\CommentManager;
use App\Manager\ViewManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CommentListener
{
    private $viewManager;
    private $commentManager;

    public function __construct(ViewManager $viewManager, CommentManager $commentManager)
    {
        $this->viewManager = $viewManager;
        $this->commentManager = $commentManager;
    }

    public function prePersist(Comment $comment, LifecycleEventArgs $event): void
    {
//        dump("create");
    }

    public function preUpdate(Comment $comment, LifecycleEventArgs $event): void
    {
//        dump("update");
    }
}