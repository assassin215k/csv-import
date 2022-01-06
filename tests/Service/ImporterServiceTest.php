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
use App\Service\CsvReader\CsvReaderService;
use App\Service\ImporterService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManager;
use League\Csv\InvalidArgument;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * ImporterServiceTest.
 */
class ImporterServiceTest extends TestCase
{
    private ImporterService $service;

    public function setUp(): void
    {
        $repository = Mockery::mock(ProductRepository::class);
        $repository->shouldReceive('removeByCodes');
        $repository->shouldReceive('findOneBy')->andReturn([]);

        $manager = Mockery::mock(EntityManager::class);
        $manager->shouldReceive('persist');
        $manager->shouldReceive('flush');
        $manager->shouldReceive('getRepository')->with(Product::class)->andReturn($repository);

        $this->service = new ImporterService(new CsvReaderService(), new ValidatorService(), $manager);
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws \League\Csv\Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testCorrectAll()
    {
        $response = $this->service->import('./tests/csvForTests/all_correct.csv');

        $this->assertEquals(10, $response->successItems);
        $this->assertEmpty($response->skippedString);
        $this->assertEmpty($response->invalidCode);
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws \League\Csv\Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testWrongRowData()
    {
        $response = $this->service->import('./tests/csvForTests/all_wrong.csv');

        $this->assertEquals(0, $response->successItems);
        $this->assertEquals(15, count($response->invalidCode));
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws \League\Csv\Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testMixed()
    {
        $response = $this->service->import('./tests/csvForTests/stock.csv');

        $this->assertEquals(19, $response->successItems);
        $this->assertSame([34], $response->skippedString);
        $this->assertSame(['P0007', 'P0009', 'P0010', 'P0011', 'P0013', 'P0015', 'P0017', 'P0026', 'P0027', 'P0028', 'P0029', 'P0030', 'P0031', 'P0032', 'P0033'], $response->invalidCode);
    }
}
