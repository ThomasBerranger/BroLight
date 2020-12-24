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
 * @Route("", name="user.")
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
     * @Route("/", name="history", methods={"GET"})
     */
    public function history(): Response
    {
        $timeline = $this->userService->getUserTimeline($this->getUser());

        return $this->render('user/history.html.twig', [
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

    /**
     * @Route("/user/{slug}", name="show", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function show(User $user): Response
    {
        return $this->render('user/details.html.twig', [
            'user' => $user
        ]);
    }
}
