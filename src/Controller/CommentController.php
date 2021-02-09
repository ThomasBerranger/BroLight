<?php

namespace App\Controller;

use App\Entity\Rate;
use App\Entity\Opinion;
use App\Entity\View;
use App\Form\OpinionType;
use App\Manager\CommentManager;
use App\Manager\RateManager;
use App\Manager\ViewManager;
use App\Service\EntityLinkerService;
use App\Service\TMDBService;
use Exception;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/comment", name="comment.")
 */
class CommentController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $entityLinkerService;
    private $tmdbService;
    private $commentManager;
    private $viewManager;
    private $rateManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, EntityLinkerService $entityLinkerService, TMDBService $tmdbService, CommentManager $commentManager, ViewManager $viewManager, RateManager $rateManager)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->entityLinkerService = $entityLinkerService;
        $this->tmdbService = $tmdbService;
        $this->commentManager = $commentManager;
        $this->viewManager = $viewManager;
        $this->rateManager = $rateManager;
    }

    /**
     * @Route("/form/{tmdbId}", name="form", methods={"GET"})
     *
     * @param int $tmdbId
     *
     * @return Response
     */
    public function form(int $tmdbId): Response
    {
        $comment = $this->commentManager->getFrom(['author'=>$this->getUser(), 'tmdbId'=>$tmdbId]);

        if (!$comment instanceof Comment)
            $comment = new Comment();

        $view = $this->viewManager->getFrom(['author'=>$this->getUser(), 'tmdbId'=>$tmdbId]);
        $rate = $this->rateManager->getFrom(['author'=>$this->getUser(), 'tmdbId'=>$tmdbId]);

        $form = $this->createForm(CommentType::class, $comment, [
            'tmdbId' => $tmdbId,
            'viewed' => $view instanceof View,
            'rateValue' => $rate instanceof Rate ? $rate->getValue() : 0
        ]);

        return $this->render('comment/_form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->getContent();

            $existingComment = $this->commentManager->getFrom([
                'author'=>$this->getUser(),
                'tmdbId'=>json_decode($data)->tmdbId
            ]);

            $comment = $this->serializer->deserialize($data, Comment::class, 'json', ['disable_type_enforcement' => true, AbstractNormalizer::OBJECT_TO_POPULATE => $existingComment]);

            !isset(json_decode($data)->spoiler) ? $comment->setSpoiler(false) : null; // Set spoiler

            $comment->setAuthor($this->getUser()); // Set author

            if (isset(json_decode($data)->viewed)) { // Create + Set / Set View
                $viewFounded = $this->entityLinkerService->findAndLinkView($comment);
                if (!$viewFounded) {
                    $view = $this->viewManager->create($comment->getTmdbId());
                    $comment->setView($view);
                }
            } else {
                $this->viewManager->delete($comment->getTmdbId());
            }

            if(isset(json_decode($data)->rate) and json_decode($data)->rate != "") { // Create Rate
                $this->rateManager->createRateIfViewExist($comment->getTmdbId(), (int) json_decode($data)->rate);
            }

            $this->commentManager->create($comment);

            return new JsonResponse([
                'view' => $this->renderView('comment/_commentButton.html.twig', ['movie' => $this->tmdbService->getMovieById($comment->getTmdbId())]),
                'commentId' => $comment->getId(),
                'tmdbId' => $comment->getTmdbId()
            ], 200);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"GET"})
     *
     * @param Comment $comment
     *
     * @return JsonResponse
     */
    public function delete(Comment $comment): JsonResponse
    {
        try {
            $this->commentManager->delete($comment);

            return $this->json(null);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }
}
