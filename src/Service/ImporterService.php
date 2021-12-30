<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45.
 */

namespace App\Service;

use App\Entity\Product;
use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Exception\ReadNotInitializedException;
use App\Misc\CsvRow;
use App\Misc\ImportResponse;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception as CsvException;
use League\Csv\InvalidArgument;

/**
 * ImporterService to read csv and import to DB.
 */
class ImporterService
{
    private ProductRepository $repository;

    /**
     * @param CsvReaderService       $reader
     * @param ValidatorService       $validator
     * @param EntityManagerInterface $manager
     */
    public function __construct(private CsvReaderService $reader, private ValidatorService $validator, private EntityManagerInterface $manager)
    {
        $this->repository = $this->manager->getRepository(Product::class);
    }

    /**
     * @throws CsvException
     * @throws EmptyFileException
     * @throws InvalidArgument
     * @throws MissedFileException
     * @throws ReadNotInitializedException
     */
    public function import(string $fileName, string $delimiter = ','): ImportResponse
    {
        $this->repository->removeByCodes();

        $this->reader->init($fileName, $delimiter);

        $limit = 1000;
        $offset = 0;
        $records = $this->reader->read($limit, $offset);

        $response = new ImportResponse();

        $productCodes = [];
        while (count($records)) {
            foreach ($records as $key => $record) {
                $this->addProduct($offset + $key, $record, $response, $productCodes);
            }

            $this->manager->flush();

            $offset = $limit;
            $limit += 1000;

            $records = $this->reader->read($limit, $offset);
        }

        return $response;
    }

    /**
     * @return void
     */
    private function addProduct(int $key, array $record, ImportResponse $response, array &$codes)
    {
        $product = new Product();
        $product->setCode($record[CsvRow::CODE]);
        $product->setCost((float) $record[CsvRow::COST]);
        $product->setName((string) $record[CsvRow::NAME]);
        $product->setDescription((string) $record[CsvRow::DESC]);
        $product->setStock((int) $record[CsvRow::STOCK]);
        $product->setDiscontinued((bool) $record[CsvRow::DISC]);

        if (in_array($product->getCode(), $codes)) {
            $response->skippedString[] = $key + 1;

            return;
        }

        if (!$this->validator->isValidProduct($product)) {
            $response->invalidCode[] = $product->getCode();

            return;
        }

        $this->manager->persist($product);

        ++$response->successItems;

        $codes[] = $product->getCode();
    }
}
