<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 29.12.21
 * Time: 09:12
 */

namespace App\Tests\Service;

use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Service\CsvReaderService;
use App\Service\ImporterService;
use App\Service\ValidatorService;
use App\Tests\AbstractCase\AbstractDatabaseCase;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\ORMException;
use League\Csv\InvalidArgument;

/**
 * ImporterServiceTest
 */
class ImporterServiceTest extends AbstractDatabaseCase
{

    private ImporterService $service;

    /**
     * @throws Exception
     * @throws ORMException
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ImporterService(new CsvReaderService(), new ValidatorService(), $this->manager);
        $this->manager->getRepository('App:Product')->removeByCodes();
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

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws \League\Csv\Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testLargeFile()
    {
        //TODO resolve too long import
//        $this->assertEquals(1001849, $this->service->import('./tests/csvForTests/large.csv')->total());
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws \League\Csv\Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testEncodingIssue()
    {
        //TODO required file in wrong(different from utf-8 ?) encoding
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws \League\Csv\Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testLineTerminationIssue()
    {
        //TODO required to understand what is it
    }
}
