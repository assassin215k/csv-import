<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 27.12.21
 * Time: 13:37.
 */

namespace App\Tests\Entity;

use App\Entity\Product;
use App\Tests\AbstractCase\AbstractDatabaseCase;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * ProductTest.
 */
class ProductTest extends AbstractDatabaseCase
{
    public function testToStringClass(): void
    {
        $product = new Product();
        $product->setCode('Code123');

        $this->assertEquals('Code123', $product);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testGettersAndSetters(): void
    {
        $product = new Product();

        $product->setCode('Code');
        $product->setCost(125.11);
        $product->setName('The product');
        $product->setDescription('Description of product');
        $product->setStock(2);
        $product->setDiscontinued(true);

        $this->manager->persist($product);
        $this->manager->flush();

        $this->assertEquals('Code', $product->getCode());
        $this->assertEquals(125.11, $product->getCost());
        $this->assertEquals('The product', $product->getName());
        $this->assertEquals('Description of product', $product->getDescription());
        $this->assertEquals(2, $product->getStock());

        $this->assertNotEmpty($product->getId());

        $this->assertInstanceOf(DateTime::class, $product->getDiscontinued());
    }
}
