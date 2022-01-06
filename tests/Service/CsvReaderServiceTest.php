<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 14:55.
 */

namespace App\Tests\Service;

use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Exception\ReadNotInitializedException;
use App\Exception\WrongCsvHeadersException;
use App\Service\CsvReader\CsvReaderService;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use PHPUnit\Framework\TestCase;

/**
 * CsvReaderServiceTest.
 */
class CsvReaderServiceTest extends TestCase
{
    /**
     * @throws EmptyFileException
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     * @throws Exception
     *
     * @return void
     */
    public function testInit()
    {
        $service = new CsvReaderService();

        $this->assertGreaterThan(0, $service->init('.info/stock.csv', ','));
    }

    /**
     * @throws Exception
     * @throws ReadNotInitializedException
     *
     * @return void
     */
    public function testNotInit()
    {
        $service = new CsvReaderService();

        $this->expectException(ReadNotInitializedException::class);
        $service->read(10, 0);
    }

    /**
     * @throws EmptyFileException
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     * @throws Exception
     * @throws ReadNotInitializedException
     *
     * @return void
     */
    public function testRead()
    {
        $service = new CsvReaderService();
        $service->init('.info/stock.csv', ',');
        $result = $service->read(10);

        $this->assertGreaterThan(0, count($result));
    }

    /**
     * @throws EmptyFileException
     * @throws Exception
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     *
     * @return void
     */
    public function testDelimiter()
    {
        $service = new CsvReaderService();

        $count = $service->init('./tests/csvForTests/delimiter.csv', '|');

        $this->assertGreaterThan(0, $count);
    }

    /**
     * @throws EmptyFileException
     * @throws Exception
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     *
     * @return void
     */
    public function testQuotes()
    {
        $service = new CsvReaderService();

        $count = $service->init('./tests/csvForTests/quotes.csv', ',');

        $this->assertGreaterThan(0, $count);
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

        $service->init('./tests/csvForTests/stock.csv', ',', ['someHeader']);
    }

    /**
     * @throws EmptyFileException
     * @throws Exception
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     *
     * @return void
     */
    public function testMissedFile()
    {
        $service = new CsvReaderService();

        $this->expectException(MissedFileException::class);

        $service->init('/wrong.csv', ',');
    }

    /**
     * @throws EmptyFileException
     * @throws Exception
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     *
     * @return void
     */
    public function testEmptyFile()
    {
        $service = new CsvReaderService();

        $this->expectException(EmptyFileException::class);

        $service->init('./tests/csvForTests/empty.csv', ',');
    }

    /**
     * @throws EmptyFileException
     * @throws Exception
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     *
     * @return void
     */
    public function testMissedHeaders()
    {
        $service = new CsvReaderService();

        $this->expectException(WrongCsvHeadersException::class);

        $service->init('./tests/csvForTests/no_headers.csv', ',');
    }
}
