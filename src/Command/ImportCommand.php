<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 13:00.
 */

namespace App\Command;

use App\Misc\ImportResponse;
use App\Service\ImporterService;
use App\Service\ReportService\IReportService;
use Exception;
use League\Csv\InvalidArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ImportCommand.
 */
class ImportCommand extends Command
{
    protected static $defaultName = 'app:csv-import';
    protected static $defaultDescription = 'Import products from provided csv file';

    private readonly int $reportKey;

    /**
     * @param ImporterService $service
     * @param IReportService  $reportService
     */
    public function __construct(public ImporterService $service, private readonly IReportService $reportService)
    {
        parent::__construct();
    }

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
            ->setHelp('This command allows you to import products from the local csv file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Command started \r\n");

        $this->reportKey = (new \DateTime())->getTimestamp();
        $this->reportService->createReport($this->reportKey);
        $report = $this->reportService->getReport($this->reportKey);
        $report->invalid = -500;

        try {
            $this->service->import($output, $this->reportKey, $input->getArgument('file'), $input->getOption('delimiter'));

            $report = $this->reportService->getReport($this->reportKey);

            $output->writeln($report);

            $output->writeln("Command successfully done!\r\n");

            return Command::SUCCESS;
        } catch (InvalidArgument $e) {
            $output->writeln('Invalid delimiter specified!');

            return Command::INVALID;
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }

        return Command::FAILURE;
    }
}
