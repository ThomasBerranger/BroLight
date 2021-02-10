<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin.")
 */
class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserManager $userManager;

    public function __construct(EntityManagerInterface $entityManager, UserManager $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    /**
     * @Route("", name="home")
     */
    public function home(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/home.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/delete/user/{id}", name="user.delete")
     *
     * @param User $user
     *
     * @return Response
     */
    public function removeUser(User $user): Response
    {
        $this->userManager->delete($user);

        return $this->redirectToRoute('admin.home');
    }
}
