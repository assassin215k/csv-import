<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45
 */

namespace App\Service;

use App\Entity\Product;
use Exception;
use League\Csv\ByteSequence;
use League\Csv\CharsetConverter;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;

/**
 * CsvReaderService to read csv file and check headers to be identical with target
 */
class CsvReaderService
{

    /**
     * @throws InvalidArgument
     * @throws \League\Csv\Exception
     * @throws Exception
     *
     * @param string $fileName
     * @param string $delimiter
     * @param array  $targetHeaders
     *
     * @return TabularDataReader
     *
     */
    public function read(string $fileName, string $delimiter, array $targetHeaders): TabularDataReader
    {
        self::checkFile($fileName);

        $csv = Reader::createFromPath($fileName);

        $inputBom = $csv->getInputBOM();

        if (ByteSequence::BOM_UTF16_LE === $inputBom || ByteSequence::BOM_UTF16_BE === $inputBom) {
            CharsetConverter::addTo($csv, 'utf-16', 'utf-8');
        }

        $csv->setDelimiter($delimiter);
        $csv->setHeaderOffset(0);

        $headers = $csv->getHeader();

        if (count(array_diff($targetHeaders, $headers))) {
            throw new Exception("Headers didn't match!", 3);
        }

        return Statement::create()->process($csv, $headers);
    }

    /**
     * @throws Exception
     *
     * @param string $fileName
     */
    private static function checkFile(string $fileName): void
    {
        if (!is_readable($fileName)) {
            throw new Exception(sprintf("File '%s' doesn't found or unavailable", $fileName), 1);
        }

        if (!filesize($fileName)) {
            throw new Exception(sprintf("File '%s' is empty!", $fileName), 2);
        }
    }
}
