<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45.
 */

namespace App\Service;

use App\Message\Row;
use App\Misc\ImportResponse;
use App\Service\CsvReader\IReader;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * ImporterService to read csv and import to DB.
 */
class ImporterService
{
    /**
     * @param IReader             $reader
     * @param MessageBusInterface $bus
     */
    public function __construct(private IReader $reader, private MessageBusInterface $bus)
    {
    }

    /**
     * @param string $fileName
     * @param string $delimiter
     *
     * @return ImportResponse
     */
    public function import(string $fileName, string $delimiter = ','): ImportResponse
    {
        $this->reader->init($fileName, $delimiter);

        $page = 1;
        $records = $this->reader->read($page);

        $response = new ImportResponse();

        while ($records->valid()) {
            foreach ($records as $key => $record) {
                $this->bus->dispatch(new Row($record, $key));
            }

            $records = $this->reader->read(++$page);
        }

        return $response;
    }
}
