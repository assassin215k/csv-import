<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45.
 */

namespace App\Service\CsvReader;

use App\Enum\Row;
use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Exception\ReadNotInitializedException;
use App\Exception\WrongCsvHeadersException;
use Iterator;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;

/**
 * CsvReaderService to read csv file and check headers to be identical with target.
 */
class LeagueReader implements IReader
{
    private bool $prepared = false;
    private Reader $reader;
    private array $headers;
    private Statement $statement;

    public function __construct(private readonly int $limit)
    {
    }

    /**
     * @throws EmptyFileException
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws WrongCsvHeadersException
     * @throws Exception
     */
    public function init(string $fileName, string $delimiter): int
    {
        self::checkFile($fileName);

        $this->reader = Reader::createFromPath($fileName);

        $this->reader->setDelimiter($delimiter);
        $this->reader->setHeaderOffset(0);

        $this->headers = $this->reader->getHeader();
        $this->checkHeaders();

        $this->statement = Statement::create();

        $this->prepared = true;

        return $this->reader->count();
    }

    /**
     * @throws ReadNotInitializedException
     * @throws Exception
     */
    public function read(int $page = 1): Iterator
    {
        if (!$this->prepared) {
            throw new ReadNotInitializedException();
        }

        if ($page < 1) {
            $page = 1;
        }

        return $this->statement->limit($this->limit)->offset(($page - 1) * $this->limit)->process($this->reader, $this->headers)->getRecords();
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

    /**
     * @throws WrongCsvHeadersException
     */
    private function checkHeaders(): void
    {
        if (count(array_diff(Row::getHeaders(), $this->reader->getHeader()))) {
            throw new WrongCsvHeadersException();
        }
    }
}
