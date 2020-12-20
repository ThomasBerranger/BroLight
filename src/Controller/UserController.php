<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Avatar;
use App\Form\UserType;
use App\Form\AvatarType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user.")
 */
class UserController extends AbstractController
{
    private $entityManager;
    private $userService;

    public function __construct(EntityManagerInterface $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
    }

    /**
     * @Route("/feed", name="feed")
     */
    public function feed(): Response
    {
        $timeline = [];

        $views = $this->userService->getUserTimeline($this->getUser());

        $timeline = $views;

        return $this->render('user/feed.html.twig', [
            'timeline' => $timeline
        ]);
    }

    /**
     * @Route("/edit", name="edit")
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

        if ($this->getUser()->getAvatar()) {
            $avatarForm = $this->createForm(AvatarType::class, $this->getUser()->getAvatar());
        } else {
            $avatarForm = $this->createForm(AvatarType::class, new Avatar());
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $form->getData();

            $this->entityManager->persist($currentUser);
            $this->entityManager->flush();
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'avatarForm' => $avatarForm->createView(),
            'users' => $this->getDoctrine()->getRepository(User::class)->findAllExcept($this->getUser())
        ]);
    }
}
