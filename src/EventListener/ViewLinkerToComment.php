<?php

namespace App\EventListener;

use App\Entity\Comment;
use App\Entity\View;
use App\Manager\CommentManager;
use App\Manager\ViewManager;
use App\Service\TMDBService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ViewLinkerToComment
{
    private $viewManager;
    private $commentManager;

    public function __construct(ViewManager $viewManager, CommentManager $commentManager)
    {
        $this->viewManager = $viewManager;
        $this->commentManager = $commentManager;
    }

    public function prePersist(View $view, LifecycleEventArgs $event): void
    {
        if (!$view->getComment() instanceof Comment) {

            $comment = $this->commentManager->getCommentFrom(['author'=>$view->getAuthor(), 'tmdbId'=>$view->getTmdbId()]);

            if ($comment instanceof Comment)
                $view->setComment($comment);
        }
    }
}