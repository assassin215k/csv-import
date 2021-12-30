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
    /**
     * @throws EmptyFileException
     * @throws MissedFileException
     * @throws InvalidArgument
     * @throws \League\Csv\Exception
     * @throws Exception
     */
    public function read(string $fileName, string $delimiter, array $targetHeaders = []): TabularDataReader
    {
        if (empty($targetHeaders)) {
            $targetHeaders = CsvRow::$headers;
        }

        self::checkFile($fileName);

        $csv = Reader::createFromPath($fileName);

        $csv->setDelimiter($delimiter);
        $csv->setHeaderOffset(0);

        $headers = $csv->getHeader();

        if (count(array_diff($targetHeaders, $headers))) {
            throw new WrongCsvHeadersException();
        }

        return Statement::create()->process($csv, $headers);
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
