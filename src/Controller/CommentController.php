<?php

namespace App\Controller;

use App\Entity\Rate;
use App\Entity\User;
use App\Entity\View;
use App\Manager\CommentManager;
use App\Manager\RateManager;
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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
    private $commentManager;
    private $viewManager;
    private $rateManager;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, ViewService $viewService, CommentManager $commentManager, ViewManager $viewManager, RateManager $rateManager)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->viewService = $viewService;
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
        $comment = $this->getDoctrine()->getRepository(Comment::class)->findOneBy(['author'=>$this->getUser(), 'tmdbId'=>$tmdbId]);

        if (!$comment instanceof Comment)
            $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, ['tmdbId' => $tmdbId]);

        if ($comment->getView() instanceof View and $comment->getView()->getRate() instanceof Rate) {
            $rate = $comment->getView()->getRate();
        } else {
            $rate = 0;
        }

        return $this->render('comment/_form.html.twig', [
            'form' => $form->createView(),
            'rate' => $rate
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

            $comment = $this->commentManager->getCommentFrom([
                'author'=>$this->getUser(),
                'tmdbId'=>json_decode($data)->tmdbId
            ]);

            /** @var Comment $comment */
            $comment = $this->serializer->deserialize($data, Comment::class, 'json', ['disable_type_enforcement' => true, AbstractNormalizer::OBJECT_TO_POPULATE => $comment]);

            $comment->setAuthor($this->getUser());

            !isset(json_decode($data)->spoiler) ? $comment->setSpoiler(false) : null;

            $errors = $validator->validate($comment);
            if (count($errors) > 0) {
                throw new Exception((string) $errors);
            }

            $view = $this->viewManager->createViewFromCommentIfNotExist($comment);

            if(isset(json_decode($data)->rate) and json_decode($data)->rate != "") {
                $this->rateManager->createRateFromView($view, (int) json_decode($data)->rate);
            }

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->json($comment, 201, [], ['groups' => 'comment:read']);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }
}
