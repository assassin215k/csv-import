<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45
 */

namespace App\Service;

use App\Entity\Product;
use App\Misc\CsvRow;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use League\Csv\InvalidArgument;

class ImporterService {
	
	private ProductRepository $repository;
	
	public function __construct(
		private CsvReaderService $reader,
		private ValidatorService $validator,
		private EntityManagerInterface $manager,
	) {
		$this->repository = $this->manager->getRepository( 'App:Product' );
	}
	
	
	#[ArrayShape( [
		'skippedItems' => "int",
		'successItems' => "int",
		'invalidItems' => "int",
	] )]
	/**
	 * @param string $fileName
	 * @param string $delimiter
	 *
	 * @return int[]
	 * @throws InvalidArgument
	 * @throws \League\Csv\Exception
	 */
	public function import( string $fileName, string $delimiter ): array {
		$reader = $this->reader->read($fileName, $delimiter, CsvRow::$headers);
		
		$skippedItems = 0;
		$successItems = 0;
		$invalidItems = 0;
		
		$productIds = [];
		foreach ( $reader->getRecords() as $record ) {
			$code    = $record[ CsvRow::CODE ];
			$product = $this
				->repository
				->findOneBy(
					[
						'code' => $code,
					]
				);
			
			if ( ! $product ) {
				$product = new Product();
				$product->setCode( $record[ CsvRow::CODE ] );
			}
			$this->fillProduct( $product, $record );
			
			
			if ( ! $this->validator->isValidProduct($product) ) {
				$invalidItems ++;

				continue;
			}
			
			try {
				$this->manager->persist( $product );
				$this->manager->flush();
				
				$successItems++;
				
				$productIds[] = $product->getId();
			} catch ( Exception $e ) {
				$serializedProduct = serialize( $product );
				
				echo "Product with code '$code' didn't save to database:\r\n$serializedProduct\r\n" . $e->getMessage() . "\r\n";
				
				$invalidItems ++;
			}
		}
		
		$this->repository->removeWithFilterById($productIds);
		
		return [
			'skippedItems' => $skippedItems,
			'successItems' => $successItems,
			'invalidItems' => $invalidItems,
		];
	}
	
	/**
	 * @param Product $product
	 * @param array   $record
	 *
	 * @return void
	 */
	private function fillProduct( Product $product, array $record ): void {
		$product->setCost( (float) $record[ CsvRow::COST ] );
		$product->setName( (string)$record[ CsvRow::NAME ] );
		$product->setDescription( (string)$record[ CsvRow::DESC ] );
		$product->setStock( (int) $record[ CsvRow::STOCK ] );
		$product->setDiscontinued( (bool) $record[ CsvRow::DISC ] );
	}
}
