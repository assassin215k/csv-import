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
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
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
     * @return int[]
     */
    #[ArrayShape([
        'skippedItems' => "int",
        'successItems' => "int",
        'invalidItems' => "int",
    ])]
    public function import(string $fileName, string $delimiter): array
    {
        $reader = $this->reader->read($fileName, $delimiter);

        $skippedItems = 0;
        $successItems = 0;
        $invalidItems = 0;

        $productCodes = [];
        foreach ($reader->getRecords() as $key => $record) {
            $product = $this->getProduct($record);

            if (!$this->validator->isValidProduct($product) || in_array($product->getCode(), $productCodes)) {
                $invalidItems++;

                continue;
            }

            $this->manager->persist($product);

            $successItems++;

            $productCodes[] = $product->getCode();

            if ($key % 10 === 0) {
                $this->manager->flush();
            }
        }

        $this->manager->flush();

        $this->repository->removeByCodes($productCodes);

        return [
            'skippedItems' => $skippedItems,
            'successItems' => $successItems,
            'invalidItems' => $invalidItems,
        ];
    }

    /**
     * @param array $record
     *
     * @return Product
     */
    private function getProduct(array $record): Product
    {
        $code = $record[CsvRow::CODE];

        $product = $this
            ->repository
            ->findOneBy(
                [
                    'code' => $code,
                ]
            );

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
