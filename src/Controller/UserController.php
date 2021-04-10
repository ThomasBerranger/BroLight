<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AvatarType;
use App\Manager\UserManager;
use App\Service\PopulateMovieService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("", name="user.")
 */
class UserController extends AbstractController
{
    private UserService $userService;
    private PopulateMovieService $populateMovieService;
    private EntityManagerInterface $entityManager;
    private UserManager $userManager;
    private SerializerInterface $serializer;

    public function __construct(UserService $userService, PopulateMovieService $populateMovieService, EntityManagerInterface $entityManager, UserManager $userManager, SerializerInterface $serializer)
    {
        $this->userService = $userService;
        $this->populateMovieService = $populateMovieService;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/", name="timeline", methods={"GET"})
     */
    public function timeline(): Response
    {
        $timelineEvents = $this->userService->getTimelineEvents($this->getUser(), 0, User::DEFAULT_TIMELINE_LIMIT);

        return $this->render('user/timeline.html.twig', ['timelineEvents' => $timelineEvents, 'timelineDefaultLimit' => User::DEFAULT_TIMELINE_LIMIT]);
    }

    /**
     * @Route("/load_timeline_events/{offset}/{limit}/{isInverted}", name="load_timeline_events", methods={"GET"})
     *
     * @param int  $offset
     * @param int  $limit
     * @param bool $isInverted
     *
     * @return Response
     */
    public function loadTimeline(int $offset = 0, int $limit = User::DEFAULT_TIMELINE_LIMIT, bool $isInverted = false): Response
    {
        $timelineEvents = $this->userService->getTimelineEvents($this->getUser(), $offset, $limit);

        return new JsonResponse([$this->renderView('user/_timeline_events.html.twig', ['timelineEvents' => $timelineEvents, 'isInverted' => $isInverted])], 200);
    }

    /**
     * @Route("/my-account", name="edit", methods={"GET"})
     *
     * @return Response
     */
    public function edit(): Response
    {
        $avatarForm = $this->createForm(AvatarType::class, $this->getUser()->getAvatar());

        $currentUserPopulatedLastOpinionsAndWishes = $this->userService->moviePopulateLasOpinionsAndWishOf($this->getUser());

        return $this->render('user/edit.html.twig', [
            'avatarForm' => $avatarForm->createView(),
            'lastCurrentUserOpinions' => $currentUserPopulatedLastOpinionsAndWishes['opinions'],
            'currentUserWishes' => $currentUserPopulatedLastOpinionsAndWishes['wishes']
        ]);
    }

    /**
     * @Route("/form", name="form", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function form(Request $request): Response
    {
        $form = $this->createForm(UserType::class, $this->getUser());

        return $this->render('user/_partials/_form.html.twig', ['form' => $form->createView()]);
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

            $user = $this->serializer->deserialize($data, User::class, 'json', ['disable_type_enforcement' => true, AbstractNormalizer::OBJECT_TO_POPULATE => $this->getUser()]);

            $this->userManager->save($user);

            return new JsonResponse(null, 204);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @Route("/user/{slug}", name="show", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function show(User $user): Response
    {
        $currentUserPopulatedLastOpinionsAndWishes = $this->userService->moviePopulateLasOpinionsAndWishOf($user);

        return $this->render('user/details.html.twig', [
            'user' => $user,
            'lastCurrentUserOpinions' => $currentUserPopulatedLastOpinionsAndWishes['opinions'],
            'currentUserWishes' => $currentUserPopulatedLastOpinionsAndWishes['wishes']
        ]);
    }
}
