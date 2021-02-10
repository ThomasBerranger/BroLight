<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Entity\User;
use App\Form\OpinionType;
use App\Manager\OpinionManager;
use App\Service\TMDBService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/opinion", name="opinion.")
 */
class OpinionController extends AbstractController
{
    private OpinionManager $opinionManager;
    private SerializerInterface $serializer;
    private TMDBService $TMDBService;

    public function __construct(OpinionManager $opinionManager, SerializerInterface $serializer, TMDBService $TMDBService)
    {
        $this->opinionManager = $opinionManager;
        $this->serializer = $serializer;
        $this->TMDBService = $TMDBService;
    }

    /**
     * @Route("/create_view/{tmdbId}", name="create_view", methods={"GET"})
     *
     * @param int $tmdbId
     *
     * @return JsonResponse
     */
    public function createView(int $tmdbId): JsonResponse
    {
        try {
            $opinion = $this->opinionManager->createView($this->getUser(), $tmdbId);

            return $this->json($opinion, 200, [], ['groups' => 'opinion:read']);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }

    /**
     * @Route("/delete_view/{tmdbId}", name="delete_view", methods={"GET"})
     *
     * @param int $tmdbId
     *
     * @return JsonResponse
     */
    public function deleteView(int $tmdbId): JsonResponse
    {
        try {
            $opinion = $this->opinionManager->deleteView($this->getUser(), $tmdbId);

            return $this->json($opinion, 200, [], ['groups' => 'opinion:read']);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
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
        $form = $this->createForm(OpinionType::class, $this->opinionManager->findOrCreate($this->getUser(), $tmdbId));

        return $this->render('opinion/_form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/update", name="update", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $data = $request->getContent();
            $decodedJson = json_decode($data);

            $existingOpinion = $this->opinionManager->findOrCreate($this->getUser(), $decodedJson->tmdbId);

            /** @var Opinion $opinion */
            $opinion = $this->serializer->deserialize($data, Opinion::class, 'json', ['disable_type_enforcement' => true, AbstractNormalizer::OBJECT_TO_POPULATE => $existingOpinion]);

            $opinion = $this->opinionManager->save($opinion);

            return new JsonResponse([
                'button' => $this->renderView('opinion/_button.html.twig', ['movie' => $this->TMDBService->getMovieById($opinion->getTmdbId())]),
                'opinionId' => $opinion->getId(),
                'tmdbId' => $opinion->getTmdbId()
            ], 200);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"GET"})
     *
     * @param Opinion $opinion
     *
     * @return JsonResponse
     */
    public function delete(Opinion $opinion): JsonResponse
    {
        try {
            $this->opinionManager->delete($opinion);

            return $this->json(204);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }
}
