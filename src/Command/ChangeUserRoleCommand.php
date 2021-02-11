<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * A console command that creates users and stores them in the database.
 *
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php bin/console app:change-user-role
 */
class ChangeUserRoleCommand extends Command
{
    protected static $defaultName = 'app:change-user-role';

    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepository $users;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $users)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Update users\' role.')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the user')
            ->addArgument('role', InputArgument::OPTIONAL, 'The new role of the user')
        ;
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('change-user-role-command');

        $email = $input->getArgument('email');
        $role = $input->getArgument('role');

        $user = $this->users->findOneBy(['email' => $email]);

        if (!$user instanceof User) {
            throw new RuntimeException(sprintf('There is no user registered with the "%s" email.', $email));
        } else if ($role !== User::ROLE_USER and $role !== User::ROLE_ADMIN) {
            throw new RuntimeException(sprintf('"%s" isn\'t a correct role name.', $role));
        }

        $user->setRoles([$role]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s account now has : %s as role.', $user->getEmail(), $role));

        $event = $stopwatch->stop('change-user-role-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('Updated user id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }

}
