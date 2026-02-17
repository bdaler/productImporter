<?php

namespace App\Command;

use App\Component\Application\ImportProductHandler;
use App\Component\Infrastructure\Factory\ProductSourceFactory;
use App\Component\Infrastructure\Parser\CsvProductSource;
use App\Component\Infrastructure\Parser\XmlProductSource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-products',
    description: 'Import products from csv file',
)]
class ImportProductsCommand extends Command
{
    public function __construct(
        private readonly ImportProductHandler $handler,
        private readonly ProductSourceFactory $factory,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'Path to CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument(name: 'file');

        $source = $this->factory->create(path: $file);
        $result = $this->handler->handle(source: $source);

        $output->writeln(messages: sprintf(
            'Imported %d products. Total sum: %.2f',
            $result->getCount(),
            $result->getTotalSum()->format()
        ));

        foreach ($result->getErrors() as $error) {
            $output->writeln(messages: $error);
        }
        return Command::SUCCESS;
    }
}
