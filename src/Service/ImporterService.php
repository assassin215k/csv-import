<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45.
 */

namespace App\Service;

use App\Message\RowMessage;
use App\Service\CsvReader\IReader;
use App\Service\ReportService\IReportService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * ImporterService to read csv and import to DB.
 */
class ImporterService
{
    /**
     * @param IReader             $reader
     * @param MessageBusInterface $bus
     * @param LoggerInterface     $logger
     * @param IReportService      $reportService
     */
    public function __construct(private IReader $reader, private MessageBusInterface $bus, private LoggerInterface $logger, private readonly IReportService $reportService)
    {
    }

    /**
     * @param OutputInterface $output
     * @param int             $reportKey
     * @param string          $fileName
     * @param string          $delimiter
     */
    public function import(OutputInterface $output, int $reportKey, string $fileName, string $delimiter = ','): void
    {
        $report = $this->reportService->getReport($reportKey);

        $total = $this->reader->init($fileName, $delimiter);
        $this->logger->info("Total rows: $total");

        $page = 1;
        $records = $this->reader->read($page);

        $output->writeln('Read rows:');
        $progressBar = new ProgressBar($output, $total);
        $progressBar->start();

        while ($records->valid()) {
            foreach ($records as $record) {
                $report->inQueue++;
                $this->bus->dispatch(new RowMessage($record, $reportKey));
                $progressBar->advance();
            }

            $records = $this->reader->read(++$page);
        }

        $progressBar->finish();
        $output->writeln("\r\nAll rows are read!\r\n");

        if ($report->inQueue) {
            $output->writeln("Wait to proceed all items is queue...\r\n");
            $progressBar = new ProgressBar($output, $total);
            $progressBar->start();

            $report->setProgressBar($progressBar);
            $progressBar->advance($total - $report->inQueue);

            while ($report->inQueue > 0) {
                sleep(1);
            }

            $progressBar->finish();
            $output->writeln("\r\nAll items are proceed!\r\n");
        }
    }
}
