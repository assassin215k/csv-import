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
use App\Service\Output\IOutputService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * ImporterService to read csv and import to DB.
 */
class ImporterService
{
    private static int $start;

    /**
     * @param IReader             $reader
     * @param MessageBusInterface $bus
     * @param LoggerInterface     $logger
     * @param ReportService       $reportService
     * @param IOutputService      $outputService
     */
    public function __construct(private IReader $reader, private MessageBusInterface $bus, private LoggerInterface $logger, private readonly ReportService $reportService, private readonly IOutputService $outputService)
    {
        self::$start = microtime(true);
    }

    /**
     * @param int             $reportKey
     * @param string          $fileName
     * @param string          $delimiter
     */
    public function import(int $reportKey, string $fileName, string $delimiter = ','): void
    {
        $output = $this->outputService->get();

        $this->reportService->init($reportKey);

        $total = $this->reader->init($fileName, $delimiter);
        $this->logger->info("Total rows: $total");

        $page = 1;
        $records = $this->reader->read($page);

        $output->writeln(self::getTime().'Read rows:');

        $progressBar = $this->outputService->getProgressBar();
        $progressBar->setMaxSteps($total);
        $progressBar->start();

        while ($records->valid()) {
            foreach ($records as $record) {
                $this->reportService->increaseQueueLength();
                $this->bus->dispatch(new RowMessage($record, $reportKey));
                $progressBar->advance();
            }

            $records = $this->reader->read(++$page);
        }

        $progressBar->finish();
        $output->writeln("\r\n");
        $output->writeln(self::getTime()."All rows are read!\r\n");

        if ($this->reportService->getQueueLength()) {
            $output->writeln(self::getTime()."Wait to proceed all items is queue...\r\n");
            $progressBar = $this->outputService->getProgressBar();
            $progressBar->setMaxSteps($total);
            $progressBar->start();

            $step = 0;
            $step = $total - $this->reportService->getQueueLength() - $step;
            $progressBar->advance($step);

            while ($this->reportService->getQueueLength() > 0) {
                $step = $total - $this->reportService->getQueueLength() - $step;
                $progressBar->advance($step);
                sleep(1);
            }

            $progressBar->finish();
            $output->writeln("\r\n");
            $output->writeln(self::getTime()."All items are proceed!\r\n");
        }
    }

    public static function getTime(): string
    {
        $milliseconds = (microtime(true) - self::$start) * 1000;
        $msec = $milliseconds % (1000 * 60 * 60);
        $sec = intdiv($milliseconds, 1000);
        $min = intdiv($milliseconds, 1000 * 60);
        $h = intdiv($milliseconds, 1000 * 60 * 60);

        return "[$h:$min:$sec.$msec] ";
    }
}
