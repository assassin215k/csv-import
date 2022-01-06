<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 04.01.22
 * Time: 14:31
 */

namespace App\MessageHandler;

use App\Message\Row;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * RowHandler
 */
class RowHandler implements MessageHandlerInterface
{
    public function __invoke(Row $record)
    {
        var_dump($record);
//        $product = new Product();
//        $product->setCode($record[CsvRow::CODE]);
//        $product->setCost((float) $record[CsvRow::COST]);
//        $product->setName((string) $record[CsvRow::NAME]);
//        $product->setDescription((string) $record[CsvRow::DESC]);
//        $product->setStock((int) $record[CsvRow::STOCK]);
//        $product->setDiscontinued((bool) $record[CsvRow::DISC]);
//
//        if (in_array($product->getCode(), $codes)) {
//            $response->skippedString[] = $key + 1;
//
//            return;
//        }
//
//        if (!$this->validator->isValidProduct($product)) {
//            $response->invalidCode[] = $product->getCode();
//
//            return;
//        }
//
//        $this->manager->persist($product);
//
//        ++$response->successItems;
//
//        $codes[] = $product->getCode();
    }
}
