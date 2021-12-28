<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 27.12.21
 * Time: 13:37
 */

namespace App\Tests\Entity;

use App\Entity\Product;
use App\Tests\AbstractCase\AbstractDatabaseCase;
use DateTime;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * ProductTest
 */
class ProductTest extends AbstractDatabaseCase
{
    private Product $product;

    /**
     * @throws Exception
     * @throws ORMException
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->product = new Product();
    }

    /**
     * @return void
     */
    public function testValidPrice(): void
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
    public function testInValidPrice(): void
    {
        $this->product->setCost(3.5);
        $this->product->setStock(9);

        $this->assertTrue($this->product->isInvalid());
    }

    /**
     * @return void
     */
    public function testToStringClass(): void
    {
        $this->product->setCode('Code123');

        $this->assertEquals('Code123', $this->product);
    }

    /**
     * @return void
     */
    public function testHasInvalidMessage(): void
    {
        $this->product->setCode('Code123');

        $this->assertNotEmpty($this->product->getInvalidMessage());
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return void
     */
    public function testId(): void
    {
        $this->fill();

        $this->manager->persist($this->product);
        $this->manager->flush();

        $this->assertNotEmpty($this->product->getId());
    }

    /**
     * @return void
     */
    public function testGettersAndSetters(): void
    {
        $this->fill();

        $this->assertEquals('Code', $this->product->getCode());
        $this->assertEquals(125.11, $this->product->getCost());
        $this->assertEquals('The product', $this->product->getName());
        $this->assertEquals('Description of product', $this->product->getDescription());
        $this->assertEquals(2, $this->product->getStock());

        $this->assertInstanceOf(DateTime::class, $this->product->getDiscontinued());
    }

    /**
     * @return void
     */
    private function fill(): void
    {
        $this->product->setCode('Code');
        $this->product->setCost(125.11);
        $this->product->setName('The product');
        $this->product->setDescription('Description of product');
        $this->product->setStock(2);
        $this->product->setDiscontinued(true);
    }
}
