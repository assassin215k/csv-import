<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 12:59
 */

namespace App\Tests\Repository;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * ProductRepositoryTest
 */
class ProductRepositoryTest extends KernelTestCase
{
    private EntityManager $manager;
    private ProductRepository $repository;

    /**
     * @throws Exception
     * @throws ORMException
     *
     * @return void
     */
    public function setUp(): void
    {
        self::bootKernel(['environment' => 'dev']);
        $this->manager = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->manager->beginTransaction();

        $this->repository = $this->manager->getRepository(Product::class);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->manager->rollback();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return void
     */
    public function testRemove()
    {
        $this->getProduct('Code1');
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
        $this->getProduct('Code1');
        $this->getProduct('Code2');
        $this->getProduct('Code3');
        $this->getProduct('Code4');

        $this->manager->flush();

        $this->assertGreaterThanOrEqual(4, count($this->repository->findAll()));
        $this->repository->removeByCodes(['Code1']);

        $this->assertEquals(1, count($this->repository->findAll()));
    }

    /**
     * @throws ORMException
     *
     * @param string $code
     */
    private function getProduct(string $code)
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
