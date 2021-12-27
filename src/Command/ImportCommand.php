<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 13:00
 */

namespace App\Command;

use App\Service\ImporterService;
use Exception;
use League\Csv\InvalidArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command run to import from csv file
 */
class ImportCommand extends Command
{

    protected static $defaultName = 'app:csv-import';
    protected static $defaultDescription = 'Import products from provided csv file';

    /**
     * @param ImporterService $service
     */
    public function __construct(public ImporterService $service)
    {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'The file name to proceed')
            ->addOption(
                'delimiter',
                null,
                InputOption::VALUE_OPTIONAL,
                'The delimiter in strings, by default is comma(,)',
                ','
            )
            ->setHelp("This command allows you to import products from the local csv file");
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Start");

        try {
            $result = $this
                ->service
                ->import(
                    $input->getArgument('file'),
                    $input->getOption('delimiter')
                );

            $output->writeln("Done:");
            $output->writeln("Skipped: ".$result['skippedItems']);
            $output->writeln("Success: ".$result['successItems']);
            $output->writeln("Invalid: ".$result['invalidItems']);

            return Command::SUCCESS;
        } catch (InvalidArgument $e) {
            $output->writeln("Invalid delimiter specified!");

            return Command::INVALID;
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }

        return Command::FAILURE;
    }
}
