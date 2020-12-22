<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Importer\DrinksImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DrinksImporterCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:import-drinks';

    private DrinksImporter $drinksImporter;

    public function __construct(DrinksImporter $drinksImporter)
    {
        $this->drinksImporter = $drinksImporter;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Imports drinks')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows us to import drinks of different categories.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Importing drinks...');

        if ($this->drinksImporter->import()) {
            $output->writeln('Success!');
        } else {
            $output->writeln('Something went wrong while importing projects. Check logs!');
        }

        return Command::SUCCESS;
    }
}
