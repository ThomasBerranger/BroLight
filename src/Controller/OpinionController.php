<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Form\OpinionType;
use App\Manager\OpinionManager;
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

    public function __construct(OpinionManager $opinionManager, SerializerInterface $serializer)
    {
        $this->opinionManager = $opinionManager;
        $this->serializer = $serializer;
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

            $opinion = $this->serializer->deserialize($data, Opinion::class, 'json', ['disable_type_enforcement' => true, AbstractNormalizer::OBJECT_TO_POPULATE => $existingOpinion]);

            $opinion = $this->opinionManager->save($opinion);

            return $this->json($opinion, 200, [], ['groups' => 'opinion:read']);
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
            $this->opinionManager->delete($opinion); // todo: create voter

            return $this->json(204);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), 500);
        }
    }
}
