<?php
namespace App\Command;

use App\Repository\DestinationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:create-user')]
class DestinationListCommand extends Command
{
    public function __construct(
        private DestinationRepository $destinationRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $destinations = $this->destinationRepository->findAll();

        $io->success(sprintf('Destinations', $count));

        return Command::SUCCESS;
    }
}