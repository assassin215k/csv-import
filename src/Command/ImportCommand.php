<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 13:00
 */

namespace App\Command;

use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Service\ImporterService;
use Exception;
use League\Csv\InvalidArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ImportCommand
 */
class ImportCommand extends Command
{

    protected static $defaultName = 'app:csv-import';
    protected static $defaultDescription = 'Import products from provided csv file';

    private OutputInterface $output;

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
        $this->output = $output;
        $output->writeln("Start");

        try {
            $this->read($input->getArgument('file'), $input->getOption('delimiter'));
        } catch (InvalidArgument $e) {
            $output->writeln("Invalid delimiter specified!");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }

        return Command::SUCCESS;
    }

    /**
     * @throws InvalidArgument
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws \League\Csv\Exception
     *
     * @param string $file
     * @param string $delimiter
     */
    private function read(string $file, string $delimiter): void
    {
        $result = $this->service->import($file, $delimiter);

        $this->output->writeln("Report:");
        $this->output->writeln($result);
    }
}
