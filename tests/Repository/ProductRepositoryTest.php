<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 12:59.
 */

namespace App\Tests\Repository;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Tests\AbstractCase\AbstractDatabaseCase;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * ProductRepositoryTest.
 */
class ProductRepositoryTest extends AbstractDatabaseCase
{
    private ProductRepository $repository;

    /**
     * @throws Exception
     * @throws ORMException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->manager->getRepository(Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return void
     */
    public function testRemove()
    {
        $this->addProduct('Code1');
        $this->manager->flush();

        $product = $this->repository->findBy(['code' => 'Code1']);

        $this->assertGreaterThanOrEqual(1, count($product));
        $this->repository->removeByCodes();

        $this->assertEquals(0, count($this->repository->findAll()));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return void
     */
    public function testRemoveWithFilterById()
    {
        $this->addProduct('Code1');
        $this->addProduct('Code2');
        $this->addProduct('Code3');
        $this->addProduct('Code4');

        $this->manager->flush();

        $this->assertGreaterThanOrEqual(4, count($this->repository->findAll()));
        $this->repository->removeByCodes(['Code1']);

        $this->assertEquals(1, count($this->repository->findAll()));
    }

    /**
     * @throws ORMException
     */
    private function addProduct(string $code)
    {
        $product = new Product();
        $product->setCode($code);
        $product->setCost(125.11);
        $product->setName('The product');
        $product->setDescription('Description of product');
        $product->setStock(2);
        $product->setDiscontinued(true);

        $this->manager->persist($product);
    }
}
