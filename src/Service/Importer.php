<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45
 */

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use League\Csv\CharsetConverter;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;

class Importer {
	
	public static array $headers = [
		"code"  => "Product Code",
		"name"  => "Product Name",
		"desc"  => "Product Description",
		"stock" => "Stock",
		"cost"  => "Cost in GBP",
		"disc"  => "Discontinued",
	];
	
	public function __construct( private ProductRepository $repository, private EntityManagerInterface $manager ) {
	}
	
	/**
	 * @throws Exception
	 */
	#[ArrayShape( [
		'skippedItems' => "int",
		'successItems' => "int",
		'invalidItems' => "int",
	] )]
	public function import( string $fileName, string $delimiter ): array {
		$this->checkFile( $fileName );
		
		$reader = $this->getData( $fileName, $delimiter );
		
		return $this->importData( $reader );
	}
	
	/**
	 * @throws Exception
	 */
	private function checkFile( string $fileName ): void {
		if ( ! is_readable( $fileName ) ) {
			throw new Exception( "File '$fileName' doesn't found or unavailable", 1 );
		}
		
		if ( ! filesize( $fileName ) ) {
			throw new Exception( "File '$fileName' is empty!", 2 );
		}
	}
	
	/**
	 * @throws InvalidArgument
	 * @throws Exception
	 */
	private function getData( string $fileName, string $delimiter ): TabularDataReader {
		$csv = Reader::createFromPath( $fileName );
		
		$input_bom = $csv->getInputBOM();
		
		if ( $input_bom === Reader::BOM_UTF16_LE || $input_bom === Reader::BOM_UTF16_BE ) {
			CharsetConverter::addTo( $csv, 'utf-16', 'utf-8' );
		}
		
		$csv->setDelimiter( $delimiter );
		$csv->setHeaderOffset( 0 );
		
		$headers = $csv->getHeader();
		self::checkHeaders( $headers );
		
		return Statement::create()->process( $csv, $headers );
	}
	
	/**
	 * @param TabularDataReader $reader
	 *
	 * @return int[]
	 */
	#[ArrayShape( [
		'skippedItems' => "int",
		'successItems' => "int",
		'invalidItems' => "int",
	] )]
	private function importData( TabularDataReader $reader ): array {
		$skippedItems = 0;
		$successItems = 0;
		$invalidItems = 0;
		
		/*
		 * Mark all record in table to be deleted, without removing from database
		 */
		$this->repository->removeAll();
		
		foreach ( $reader->getRecords() as $record ) {
			if ( $record[ self::$headers['cost'] ] > 1000 ) {
				$skippedItems ++;
				continue;
			}
			if ( $record[ self::$headers['cost'] ] < 5 && $record[ self::$headers['stock'] ] < 10 ) {
				$skippedItems ++;
				continue;
			}
			
			$code    = $record[ self::$headers['code'] ];
			$product = $this
				->repository
				->findOneBy(
					[
						'code' => $code,
					]
				);
			
			if ( ! $product ) {
				$product = new Product();
				$product->setCode( $code );
			}
			$this->fillProduct( $product, $record );
			
			$isValid = true;//todo validate record here
			//if is valid, persist
			if ( ! $isValid ) {
				$invalidItems ++;
				
				continue;
			}
			
			$this->manager->persist( $product );
		}
		try {
			$this->manager->flush();
		} catch ( Exception $e ) {
			$serializedProduct = serialize( $product );
			var_dump($e->getMessage());
			var_dump($e->getMessage());
			echo "Product with code '$code' didn't save to database:\r\n$serializedProduct\r\n";
			
			$invalidItems ++;
			die;
		}
		
		return [
			'skippedItems' => $skippedItems,
			'successItems' => $successItems,
			'invalidItems' => $invalidItems,
		];
	}
	
	private function fillProduct( Product $product, array $record ): void {
		$product->setCost( (float) $record[ self::$headers['code'] ] );
		$product->setName( $record[ self::$headers['name'] ] );
		$product->setDescription( $record[ self::$headers['desc'] ] );
		$product->setStock( (int) $record[ self::$headers['stock'] ] );
		$product->setIsDiscontinued( (bool) $record[ self::$headers['disc'] ] );
		$product->setIsDeleted( false );
	}
	
	/**
	 * @throws Exception
	 */
	private static function checkHeaders( array &$headers ): void {
		if ( count( array_diff( self::$headers, $headers ) ) ) {
			
			throw new Exception( "Headers didn't match!", 3 );
		}
	}
}
