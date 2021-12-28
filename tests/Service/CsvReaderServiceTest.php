<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 14:55
 */

namespace App\Tests\Service;

use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Exception\WrongCsvHeadersException;
use App\Misc\CsvRow;
use App\Service\CsvReaderService;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use PHPUnit\Framework\TestCase;

/**
 * CsvReaderServiceTest
 */
class CsvReaderServiceTest extends TestCase
{
    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testRead()
    {
        $service = new CsvReaderService();
        $reader = $service->read('.info/stock.csv', ',');

        $this->assertGreaterThan(0, $reader->count());
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testDelimiter()
    {
        $service = new CsvReaderService();

        $reader = $service->read('.info/delimiter.csv', '|');

        $this->assertGreaterThan(0, $reader->count());
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testQuotes()
    {
        $service = new CsvReaderService();

        $reader = $service->read('.info/quotes.csv', ',');

        $this->assertGreaterThan(0, $reader->count());
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testWrongHeaders()
    {
        $service = new CsvReaderService();

        $this->expectException(WrongCsvHeadersException::class);

        $service->read('.info/stock.csv', ',', ['someHeader']);
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testMissedFile()
    {
        $service = new CsvReaderService();

        $this->expectException(MissedFileException::class);

        $service->read('.info/wrong.csv', ',');
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws Exception
     * @throws InvalidArgument
     *
     * @return void
     */
    public function testEmptyFile()
    {
        $service = new CsvReaderService();

        $this->expectException(EmptyFileException::class);

        $service->read('.info/empty.csv', ',');
    }
}
