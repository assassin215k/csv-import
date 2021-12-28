<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 */

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Remove products with filter by array of product codes
     *
     * @param array $codes
     *
     * @return void
     */
    public function removeWithFilterById(array $codes): void
    {
        $qb = $this->createQueryBuilder('p')->delete();

        if (count($codes)) {
            $qb->andWhere('p.code not in(:codes)')->setParameter('codes', $codes);
        }

        $qb->getQuery()->execute();
    }
}
