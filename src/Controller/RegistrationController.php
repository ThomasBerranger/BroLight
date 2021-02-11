<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserEvent;
use App\Form\RegistrationType;
use App\Manager\UserManager;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegistrationController extends AbstractController
{
    private EventDispatcherInterface $eventDispatcher;
    private UserManager              $userManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, UserManager $userManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userManager = $userManager;
    }

    /**
     * @Route("/register", name="app_register")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler    $guardHandler
     * @param LoginFormAuthenticator       $authenticator
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->userManager->save($user);

//            $event = new UserEvent($user);
//            $this->eventDispatcher->dispatch($event, UserEvent::USER_CREATE_EVENT); // todo: remplacer par un doctrine event

            $this->addFlash('firstTime','Bienvenue');

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('security/register.html.twig', ['registrationForm' => $form->createView()]);
    }
}
