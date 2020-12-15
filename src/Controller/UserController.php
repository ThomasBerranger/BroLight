<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/feed", name="feed")
     */
    public function feed(): Response
    {
        return $this->render('user/feed.html.twig');
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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $form->getData();

             $this->entityManager->persist($currentUser);
             $this->entityManager->flush();
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'users' => $this->getDoctrine()->getRepository(User::class)->findAllExcept($this->getUser())
        ]);
    }
}
