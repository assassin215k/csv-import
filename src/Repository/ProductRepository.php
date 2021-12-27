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
     * Remove products by filter of id
     *
     * @param array $ids
     *
     * @return void
     */
    public function removeWithFilterById(array $ids): void
    {
        $qb = $this->createQueryBuilder('p')->delete();

        if (count($ids)) {
            $qb->andWhere('p.id not in(:ids)')->setParameter('ids', $ids);
        }

        $qb->getQuery()->execute();
    }
}
