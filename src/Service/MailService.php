<?php

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;

class MailService
{
    private Security $security;
    private MailerInterface $mailerInterface;
    private LoggerInterface $logger;

    public function __construct(Security $security, MailerInterface $mailerInterface, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->mailerInterface = $mailerInterface;
        $this->logger = $logger;
    }

    public function sendMail(User $user, string $template, array $data)
    {
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject($data['subject'])
            ->htmlTemplate($template)
            ->context($data['context'])
        ;

        try {
            $this->mailerInterface->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Mail sending fail ! Error message :'.$e->getMessage());
        }
    }
}