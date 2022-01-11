<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 29.12.21
 * Time: 09:12.
 */

namespace App\Tests\Service;

use App\Entity\Product;
use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Repository\ProductRepository;
use App\Service\CsvReader\LeagueReader;
use App\Service\ImporterService;
use App\Service\ReportService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManager;
use League\Csv\InvalidArgument;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * ImporterServiceTest.
 */
class ImporterServiceTest extends TestCase
{
    private ImporterService $service;
    private OutputInterface $output;

    public function setUp(): void
    {
        $reportService = Mockery::mock(ReportService::class);
        $reportService->shouldReceive('getQueueLength')->andReturn(0);
        $reportService->shouldReceive('init');
        $reportService->shouldReceive('increaseQueueLength');
//        $repository->shouldReceive('removeByCodes');
//        $repository->shouldReceive('findOneBy')->andReturn([]);

//        $manager = Mockery::mock(EntityManager::class);
//        $manager->shouldReceive('persist');
//        $manager->shouldReceive('flush');
//        $manager->shouldReceive('getRepository')->with(Product::class)->andReturn($repository);
//
        $bus = Mockery::mock(MessageBusInterface::class);
        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('info');
        $this->output = Mockery::mock(ConsoleOutput::class);
        $this->output->shouldReceive('writeln');
        $this->output->shouldReceive('isDecorated')->andReturn(true);
        $this->output->shouldReceive('getVerbosity');
        $this->output->shouldReceive('getFormatter');
        $this->output->shouldReceive('getErrorOutput');

        $this->service = new ImporterService(new LeagueReader(1000), $bus, $logger, $reportService);
    }

    /**
     * @return void
     */
    public function testCorrectAll()
    {
        $this->service->import($this->output,100, './tests/csvForTests/all_correct.csv');
        $this->expectNotToPerformAssertions();
    }
//
//    /**
//     * @throws EmptyFileException
//     * @throws MissedFileException
//     * @throws \League\Csv\Exception
//     * @throws InvalidArgument
//     *
//     * @return void
//     */
//    public function testWrongRowData()
//    {
//        $response = $this->service->import('./tests/csvForTests/all_wrong.csv');
//
//        $this->assertEquals(0, $response->successItems);
//        $this->assertEquals(15, count($response->invalidCode));
//    }
//
//    /**
//     * @throws EmptyFileException
//     * @throws MissedFileException
//     * @throws \League\Csv\Exception
//     * @throws InvalidArgument
//     *
//     * @return void
//     */
//    public function testMixed()
//    {
//        $response = $this->service->import('./tests/csvForTests/stock.csv');
//
//        $this->assertEquals(19, $response->successItems);
//        $this->assertSame([34], $response->skippedString);
//        $this->assertSame(['P0007', 'P0009', 'P0010', 'P0011', 'P0013', 'P0015', 'P0017', 'P0026', 'P0027', 'P0028', 'P0029', 'P0030', 'P0031', 'P0032', 'P0033'], $response->invalidCode);
//    }
}
