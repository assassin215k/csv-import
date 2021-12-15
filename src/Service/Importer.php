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
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use League\Csv\CharsetConverter;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;

class Importer {
	
	private static array $headers = [
		"code"  => "Product Code",
		"name"  => "Product Name",
		"desc"  => "Product Description",
		"stock" => "Stock",
		"cost"  => "Cost in GBP",
		"disc"  => "Discontinued",
	];
	
	private ObjectManager $manager;
	
	public function __Construct( public ProductRepository $repository, ManagerRegistry $doctrine ) {
		$this->manager = $doctrine->getManager();
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
			
			$isValid = false;//todo validate record here
			//if is valid, persist
			if ( ! $isValid ) {
				$invalidItems ++;
				
				continue;
			}
			
			$this->manager->persist( $product );
			try {
				$this->manager->flush();
			} catch ( Exception $e ) {
				$serializedProduct = serialize( $product->getCode() );
				
				echo "Product with code '$code' didn't save to database:\r\n$serializedProduct\r\n";
				
				$invalidItems ++;
			}
		}
		
		return [
			'skippedItems' => $skippedItems,
			'successItems' => $successItems,
			'invalidItems' => $invalidItems,
		];
	}
	
	private function fillProduct( Product $product, array $record ): void {
		$product->setCost( $record[ self::$headers['code'] ] );
		$product->setName( $record[ self::$headers['name'] ] );
		$product->setDescription( $record[ self::$headers['desc'] ] );
		$product->setStock( $record[ self::$headers['stock'] ] );
		
		var_dump( $record[ self::$headers['disc'] ] );
		if ( $record[ self::$headers['disc'] ] ) {
			//todo check and test boolean here
		}
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
