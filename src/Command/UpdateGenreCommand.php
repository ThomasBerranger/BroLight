<?php

namespace App\Command;

use App\Entity\Genre;
use App\Manager\GenreManager;
use App\Service\TMDBService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * A console command that creates users and stores them in the database.
 *
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php bin/console app:update-genre
 */
class UpdateGenreCommand extends Command
{
    protected static $defaultName = 'app:update-genre';

    /**
     * @var SymfonyStyle
     */
    private SymfonyStyle $io;

    private EntityManagerInterface $entityManager;
    private TMDBService $TMDBService;

    public function __construct(EntityManagerInterface $em, TMDBService $TMDBService)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->TMDBService = $TMDBService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Update Genre table from TMDB data.');
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
        $stopwatch->start('update-genre-command');

        $TMDBServiceResult = $this->TMDBService->updateGenreList();

        foreach ($TMDBServiceResult['genres'] as $genre) {
            if(!$this->entityManager->getRepository(Genre::class)->findOneBy(['tmdbId' => $genre['id']])) {
                $newGenre = new Genre();
                $newGenre->setTmdbId($genre['id']);
                $newGenre->setName($genre['name']);

                $this->entityManager->persist($newGenre);
                $this->entityManager->flush();

                $this->io->success(sprintf('%s was successfully added.', $genre['name']));
            } else {
                $this->io->writeln(sprintf('%s was already registered.', $genre['name']));
            }
        }

        $stopwatch->stop('update-genre-command');

        return Command::SUCCESS;
    }
}
