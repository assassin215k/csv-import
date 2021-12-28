<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 16:03
 */

namespace App\Tests\AbstractCase;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * AbstractDatabaseCase
 */
class AbstractDatabaseCase extends KernelTestCase
{
    protected EntityManager $manager;

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
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->manager->rollback();
    }
}
