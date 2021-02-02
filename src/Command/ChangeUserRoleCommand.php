<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeUserRoleCommand extends Command
{
    private $entityManager;
    protected static $defaultName = 'app:change-user-role';

    public function __construct(string $name = null, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'The id of the user.')
            ->addArgument('role', InputArgument::REQUIRED, 'The ROLE to attribute.')
            ->setDescription('Change existing user role.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'User Role Update',
            '============',
        ]);

        $user = $this->entityManager->getRepository(User::class)->find($input->getArgument('id'));

        if ($user instanceof User) {

            $user->setRoles(['ROLE_'.$input->getArgument('role')]);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln('User successfully updated!');

            return Command::SUCCESS;
        }

        $output->writeln('User update failed.');

        return Command::FAILURE;
    }
}