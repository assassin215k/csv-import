<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Product|null findOneBy( array $criteria, array $orderBy = null )
 * @method Product[]    findAll()
 * @method Product[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class ProductRepository extends ServiceEntityRepository {
	
	public function __construct( ManagerRegistry $registry ) {
		parent::__construct( $registry, Product::class );
	}
	
	// /**
	//  * @return ProductData[] Returns an array of ProductData objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('p.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/
	
	/*
	public function findOneBySomeField($value): ?ProductData
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
	
	public function removeAll(): void {
		$this
			->createQueryBuilder( 'p' )
			->update()
			->set( 'p.isDeleted', true )
			->getQuery()
			->execute();
	}
}
