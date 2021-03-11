<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AvatarType;
use App\Manager\UserManager;
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
    private EntityManagerInterface $entityManager;
    private UserManager $userManager;
    private UserService $userService;

    public function __construct(EntityManagerInterface $entityManager, UserManager $userManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->userService = $userService;
    }

    /**
     * @Route("/", name="timeline", methods={"GET"})
     */
    public function timeline(): Response
    {
        $timelineEvents = $this->userService->getTimelineEvents($this->getUser());

        return $this->render('user/timeline.html.twig', ['timelineEvents' => $timelineEvents]);
    }

    /**
     * @Route("/load_timeline_events/{offset}", name="load_timeline_events", methods={"GET"})
     *
     * @param int $offset
     *
     * @return Response
     */
    public function loadTimeline(int $offset): Response
    {
        $timelineEvents = $this->userService->getTimelineEvents($this->getUser(), $offset);

        return new JsonResponse([$this->renderView('user/_timeline_events.html.twig', ['timelineEvents' => $timelineEvents])], 200);
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

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'avatarForm' => $avatarForm->createView(),
            'podium' => $this->userService->formattedPodium($this->getUser()),
            'users' => $this->getDoctrine()->getRepository(User::class)->findAllExcept($this->getUser()), //todo remplacer par une recherche ajax
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
        return $this->render('user/details.html.twig', ['user' => $user, 'podium' => $this->userService->formattedPodium($user)]);
    }
}
