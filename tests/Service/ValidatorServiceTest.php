<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 14:30.
 */

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\ValidatorService;
use PHPUnit\Framework\TestCase;

/**
 * ValidatorServiceTest.
 */
class ValidatorServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testValidProduct()
    {
        $validator = new ValidatorService();

        $product = new Product();
        $product->setCode('Code');
        $product->setCost(125.11);
        $product->setName('The product');
        $product->setDescription('Description of product');
        $product->setStock(2);
        $product->setDiscontinued(true);

        $this->assertTrue($validator->isValidProduct($product));
    }

    /**
     * @return void
     */
    public function testInValidProduct()
    {
        $validator = new ValidatorService();

        $product = new Product();

        $this->assertFalse($validator->isValidProduct($product));
    }
}
