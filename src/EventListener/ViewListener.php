<?php

namespace App\EventListener;

use App\Entity\View;
use App\Entity\Comment;
use App\Manager\CommentManager;
use App\Service\TMDBService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ViewListener
{
    private $commentManager;
    private $TMDBService;

    public function __construct(CommentManager $commentManager, TMDBService $TMDBService)
    {
        $this->TMDBService = $TMDBService;
        $this->commentManager = $commentManager;
    }

    public function postLoad(View $view, LifecycleEventArgs $event): void
    {
        $view->setMovie($this->TMDBService->getMovieById($view->getTmdbId()));
    }

    public function prePersist(View $view, LifecycleEventArgs $event): void
    {
        if (!$view->getComment() instanceof Comment) {

            $comment = $this->commentManager->getFrom(['author'=>$view->getAuthor(), 'tmdbId'=>$view->getTmdbId()]);

            if ($comment instanceof Comment)
                $view->setComment($comment);
        }
    }
}