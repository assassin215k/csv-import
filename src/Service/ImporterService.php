<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45
 */

namespace App\Service;

use App\Entity\Product;
use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Misc\CsvRow;
use App\Misc\ImportResponse;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception as CsvException;
use League\Csv\InvalidArgument;

/**
 * ImporterService to read csv and import to DB
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
        $this->repository = $this->manager->getRepository('App:Product');
    }

    /**
     * @throws CsvException
     * @throws InvalidArgument
     * @throws EmptyFileException
     * @throws MissedFileException
     *
     * @param string $fileName
     * @param string $delimiter
     *
     * @return ImportResponse
     */
    public function import(string $fileName, string $delimiter = ','): ImportResponse
    {
        $reader = $this->reader->read($fileName, $delimiter);

        $response = new ImportResponse();

        $productCodes = [];
        foreach ($reader->getRecords() as $key => $record) {
            $this->addProduct($key, $record, $response, $productCodes);

            if ($key % 10 === 0) {
                $this->manager->flush();
            }
        }

        $this->manager->flush();

        $this->repository->removeByCodes($productCodes);

        return $response;
    }

    /**
     * @param int            $key
     * @param array          $record
     * @param ImportResponse $response
     * @param array          $codes
     *
     * @return void
     */
    private function addProduct(int $key, array $record, ImportResponse $response, array &$codes)
    {
        $product = $this->makeProduct($record);

        if (in_array($product->getCode(), $codes)) {
            $response->skippedString[] = $key + 1;

            return;
        }

        if (!$this->validator->isValidProduct($product)) {
            $response->invalidCode[] = $product->getCode();

            return;
        }

        $this->manager->persist($product);

        $response->successItems++;

        $codes[] = $product->getCode();
    }

    /**
     * @param array $record
     *
     * @return Product
     */
    private function makeProduct(array $record): Product
    {
        $code = $record[CsvRow::CODE];

        $product = $this
            ->repository
            ->findOneBy(['code' => $code]);

        if (!$product) {
            $product = new Product();
            $product->setCode($code);
        }

        $product->setCost((float) $record[CsvRow::COST]);
        $product->setName((string) $record[CsvRow::NAME]);
        $product->setDescription((string) $record[CsvRow::DESC]);
        $product->setStock((int) $record[CsvRow::STOCK]);
        $product->setDiscontinued((bool) $record[CsvRow::DISC]);

        return $product;
    }
}
