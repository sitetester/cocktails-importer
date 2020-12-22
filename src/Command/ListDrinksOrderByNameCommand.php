<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ListDrinksOrderByName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListDrinksOrderByNameCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:list-drinks-order-by-name';

    private ListDrinksOrderByName $listDrinksOrderByName;

    public function __construct(ListDrinksOrderByName $listDrinksOrderByName)
    {
        $this->listDrinksOrderByName = $listDrinksOrderByName;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('List drinks order by name')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows us to list drinks order by name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);

        $list = $this->listDrinksOrderByName->getList();
        $table
            ->setHeaders($list['headers'])
            ->setRows($list['rows'])
            ->render();

        return Command::SUCCESS;
    }
}
