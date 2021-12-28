<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 27.12.21
 * Time: 13:37
 */

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

/**
 * ProductTest
 */
class ProductTest extends TestCase
{
    private Product $product;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->product = new Product();
    }

    /**
     * @return void
     */
    public function testValidPrice():void
    {
        $this->product->setCost(15);
        $this->product->setStock(11);

        $this->assertNotTrue($this->product->isInvalid());

        $this->product->setCost(1);
        $this->product->setStock(11);

        $this->assertNotTrue($this->product->isInvalid());

        $this->product->setCost(20);
        $this->product->setStock(1);

        $this->assertNotTrue($this->product->isInvalid());
    }

    /**
     * @return void
     */
    public function testInValidPrice():void
    {
        $this->product->setCost(3.5);
        $this->product->setStock(9);

        $this->assertTrue($this->product->isInvalid());
        $this->assertTrue($this->product->isInvalid());
    }
}
