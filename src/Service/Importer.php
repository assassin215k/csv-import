<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45
 */

namespace App\Service;

use Exception;
use Iterator;
use JetBrains\PhpStorm\ArrayShape;
use League\Csv\InvalidArgument;
use League\Csv\Reader;

class Importer {
	
	private static array $headers = [
		"code"  => "Product Code",
		"name"  => "Product Name",
		"desc"  => "Product Description",
		"stock" => "Stock",
		"cost"  => "Cost in GBP",
		"disc"  => "Discontinued",
	];
	
	/**
	 * @throws Exception
	 */
	#[ArrayShape( [
		'skippedItems' => "int",
		'newItems'     => "int",
		'updatedItems' => "int",
		'invalidItems' => "int"
	] )]
	public function import( string $fileName, string $delimiter ): array {
		$this->checkFile( $fileName );
		
		$iterator = $this->getData( $fileName, $delimiter );
		
		return $this->importData( $iterator );
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
	private function getData( string $fileName, string $delimiter ): Iterator {
		$csv = Reader::createFromPath( $fileName, 'r' );
		
		$csv->setDelimiter( $delimiter );
		$csv->setHeaderOffset( 0 );
		
		$headers = $csv->getHeader();
		self::checkHeaders( $headers );
		
		return $csv->getRecords( $headers );
	}
	
	/**
	 * @param Iterator $records
	 *
	 * @return int[]
	 */
	#[ArrayShape( [
		'skippedItems' => "int",
		'newItems'     => "int",
		'updatedItems' => "int",
		'invalidItems' => "int"
	] )]
	private function importData( Iterator $records ): array {
		$headers = $records->current();
		$records->next();
		
		$skippedItems = 0;
		$newItems     = 0;
		$updatedItems = 0;
		$invalidItems = 0;
		
		list( $cost, $stock ) = self::getCostIndex( $headers );
		
		foreach ( $records as $record ) {
			if ( $record[ $cost ] > 1000 ) {
				$skippedItems ++;
				continue;
			}
			if ( $record[ $cost ] < 5 && $record[ $stock ] < 10 ) {
				$skippedItems ++;
				continue;
			}
			
			//todo continue here
		}
		
		return [
			'skippedItems' => $skippedItems,
			'newItems'     => $newItems,
			'updatedItems' => $updatedItems,
			'invalidItems' => $invalidItems,
		];
	}
	
	/**
	 * @throws Exception
	 */
	private static function checkHeaders( array &$headers ): void {
		if ( count( array_diff( self::$headers, $headers ) ) ) {
			
			throw new Exception( "Headers didn't match!", 3 );
		}
	}
	
	private static function getCostIndex( array $headers ): array {
		$headers = array_flip( $headers );
		
		return [ $headers[ self::$headers['cost'] ], $headers[ self::$headers['stock'] ] ];
	}
}
