<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\ViewManager;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/comment", name="comment.")
 */
class CommentController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $viewService;
    private $viewManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ViewService $viewService, ViewManager $viewManager)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->viewService = $viewService;
        $this->viewManager = $viewManager;
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
     * @param Request            $request
     * @param ValidatorInterface $validator
     *
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        try {
            $data = $request->getContent();

            /** @var Comment $comment */
            $comment = $this->serializer->deserialize($data, Comment::class, 'json', ['disable_type_enforcement' => true]);

            $comment->setAuthor($this->getUser());

            !isset(json_decode($data)->spoiler) ? $comment->setSpoiler(false) : null;

            $errors = $validator->validate($comment);
            if (count($errors) > 0) {
                throw new Exception((string) $errors);
            }

            isset(json_decode($data)->viewed) ? $this->viewManager->createViewFromCommentIfNotExist($comment) : null;

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->json($comment, 201, [], ['groups' => 'comment:read']);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }
}
