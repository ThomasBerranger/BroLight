<?php

namespace App\Controller;

use App\Entity\Podium;
use App\Entity\User;
use App\Form\UserType;
use App\Form\AvatarType;
use App\Service\TMDBService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("", name="user.")
 */
class UserController extends AbstractController
{
    private $entityManager;
    private $userService;
    private $tmdbService;

    public function __construct(EntityManagerInterface $entityManager, UserService $userService, TMDBService $tmdbService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->tmdbService = $tmdbService;
    }

    /**
     * @Route("/", name="timeline", methods={"GET"})
     */
    public function timeline(): Response
    {
        $timeline = $this->userService->getTimeline($this->getUser());

        return $this->render('user/timeline.html.twig', [
            'timeline' => $timeline
        ]);
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

            $this->entityManager->persist($currentUser);
            $this->entityManager->flush();
        }

        foreach ($currentUser->getViews() as $view) {
            $view->setMovie($this->tmdbService->getMovieById($view->getTmdbId()));
        }

        if ($currentUser->getPodium() instanceof Podium) {
            $userPodium = [
                1 => $currentUser->getPodium()->getFirstTmdbId() ? $this->tmdbService->getMovieById($currentUser->getPodium()->getFirstTmdbId()) : null,
                2 => $currentUser->getPodium()->getSecondTmdbId() ? $this->tmdbService->getMovieById($currentUser->getPodium()->getSecondTmdbId()) : null,
                3 => $currentUser->getPodium()->getThirdTmdbId() ? $this->tmdbService->getMovieById($currentUser->getPodium()->getThirdTmdbId()) : null,
            ];
        } else {
            $userPodium = [];
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'avatarForm' => $avatarForm->createView(),
            'users' => $this->getDoctrine()->getRepository(User::class)->findAllExcept($this->getUser()),
            'userPodium' => $userPodium
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
        foreach ($user->getViews() as $view) {
            $view->setMovie($this->tmdbService->getMovieById($view->getTmdbId()));
        }

        if ($user->getPodium() instanceof Podium) {
            $userPodium = [
                1 => $user->getPodium()->getFirstTmdbId() ? $this->tmdbService->getMovieById($user->getPodium()->getFirstTmdbId()) : null,
                2 => $user->getPodium()->getSecondTmdbId() ? $this->tmdbService->getMovieById($user->getPodium()->getSecondTmdbId()) : null,
                3 => $user->getPodium()->getThirdTmdbId() ? $this->tmdbService->getMovieById($user->getPodium()->getThirdTmdbId()) : null,
            ];
        } else {
            $userPodium = [];
        }

        return $this->render('user/details.html.twig', [
            'user' => $user,
            'userPodium' => $userPodium
        ]);
    }
}
