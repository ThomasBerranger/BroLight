<?php

namespace App\Controller;

use Exception;
use App\Entity\Avatar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/avatar", name="avatar")
 */
class AvatarController extends AbstractController
{
    private $entityManager;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/edit", name=".edit", methods={"PUT"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {
        try {
            /** @var Avatar $avatar */
            $avatar = $this->getUser()->getAvatar();
            $data = $request->getContent();

            $avatar = $this->serializer->deserialize($data, Avatar::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $avatar]);

            $this->entityManager->persist($avatar);
            $this->entityManager->flush();

            return $this->json($avatar, 201, [], ['groups' => 'avatar:read']);
        } catch (Exception $exception) {
            return $this->json(['message' => $exception->getMessage()], 500);
        }
    }
}
