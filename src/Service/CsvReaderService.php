<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45.
 */

namespace App\Service;

use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Exception\ReadNotInitializedException;
use App\Exception\WrongCsvHeadersException;
use App\Misc\CsvRow;
use Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;

/**
 * CsvReaderService to read csv file and check headers to be identical with target.
 */
class CsvReaderService
{
    private bool $prepared = false;
    private Reader $reader;
    private array $headers;
    private Statement $statement;

    /**
     * @throws EmptyFileException
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     * @throws \League\Csv\Exception
     *
     * @param array  $targetHeaders
     * @param string $fileName
     * @param string $delimiter
     *
     * @return int
     */
    public function init(string $fileName, string $delimiter, array $targetHeaders = []): int
    {
        if (empty($targetHeaders)) {
            $targetHeaders = CsvRow::$headers;
        }

        self::checkFile($fileName);

        $this->reader = Reader::createFromPath($fileName);

        $this->reader->setDelimiter($delimiter);
        $this->reader->setHeaderOffset(0);

        $this->headers = $this->reader->getHeader();

        if (count(array_diff($targetHeaders, $this->headers))) {
            throw new WrongCsvHeadersException();
        }

        $this->statement = Statement::create();

        $this->prepared = true;

        return $this->reader->count();
    }

    /**
     * @throws ReadNotInitializedException
     * @throws \League\Csv\Exception
     */
    public function read(int $limit, int $offset = 0): TabularDataReader
    {
        if (!$this->prepared) {
            throw new ReadNotInitializedException();
        }

        return $this->statement->limit($limit)->offset($offset)->process($this->reader, $this->headers);
    }

    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     */
    private static function checkFile(string $fileName): void
    {
        if (!is_readable($fileName)) {
            throw new MissedFileException($fileName);
        }

        if (!filesize($fileName)) {
            throw new EmptyFileException($fileName);
        }
    }
}
