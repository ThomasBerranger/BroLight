<?php

namespace App\Controller;

use App\Service\ViewService;
use Exception;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/comment", name="comment.")
 */
class CommentController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $viewService;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ViewService $viewService)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->viewService = $viewService;
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
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, ['tmdbId' => $tmdbId]);

        return $this->render('comment/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        try {
            $data = $request->getContent();

            /** @var Comment $comment */
            $comment = $this->serializer->deserialize($data, Comment::class, 'json', ['disable_type_enforcement' => true]);

            $comment->setAuthor($this->getUser());

            !isset(json_decode($data)->spoiler) ? $comment->setSpoiler(false) : null;

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            isset(json_decode($data)->viewed) ? $this->viewService->createViewFromComment($comment) : null;

            return $this->json($comment, 201, [], ['groups' => 'comment:read']);
        } catch (Exception $exception) {
            return $this->json($exception, 500);
        }
    }
}
