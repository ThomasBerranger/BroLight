<?php

namespace App\Command;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailTestCommand extends Command
{
    protected static $defaultName = 'app:send-email-test';
    protected static $defaultDescription = 'A test mail send command';
    private MailerInterface $mailerInterface;

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    public function __construct(MailerInterface $mailerInterface)
    {
        parent::__construct();
        $this->mailerInterface = $mailerInterface;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = (new TemplatedEmail())
            ->to('blackthomas614@gmail.com')
            ->subject('Changement de mot de passe')
            ->htmlTemplate('emails/reset-password.html.twig')
            ->context([
                'user' => 'Thomas Berranger',
                'password' => '063985',
                'link' => 'https://brolight.herokuapp.com/'
            ])
        ;

        try {
            $this->mailerInterface->send($email);

            $io->success('Mail successfully send !');
            return Command::SUCCESS;
        } catch (TransportExceptionInterface $e) {
            dump($e->getMessage());

            $io->success('Mail sending failed.');
            return Command::FAILURE;
        }
    }
}
