<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AvatarType;
use App\Manager\UserManager;
use App\Service\PopulateMovieService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", name="user.")
 */
class UserController extends AbstractController
{
    private UserService $userService;
    private PopulateMovieService $populateMovieService;
    private EntityManagerInterface $entityManager;
    private UserManager $userManager;

    public function __construct(UserService $userService, PopulateMovieService $populateMovieService, EntityManagerInterface $entityManager, UserManager $userManager)
    {
        $this->userService = $userService;
        $this->populateMovieService = $populateMovieService;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
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
     * @Route("/my-account", name="edit", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Request $request): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $form = $this->createForm(UserType::class, $currentUser);

        $avatarForm = $this->createForm(AvatarType::class, $this->getUser()->getAvatar());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $form->getData();

            $this->userManager->save($currentUser);
        }

        $currentUserPopulatedLastOpinionsAndWishes = $this->userService->moviePopulateLasOpinionsAndWishOf($currentUser);

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'avatarForm' => $avatarForm->createView(),
            'lastCurrentUserOpinions' => $currentUserPopulatedLastOpinionsAndWishes['opinions'],
            'currentUserWishes' => $currentUserPopulatedLastOpinionsAndWishes['wishes']
        ]);
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
