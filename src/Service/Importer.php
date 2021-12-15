<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45
 */

namespace App\Service;

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
		
		if ($input_bom === Reader::BOM_UTF16_LE || $input_bom === Reader::BOM_UTF16_BE) {
			CharsetConverter::addTo($csv, 'utf-16', 'utf-8');
		}
		
		$csv->setDelimiter( $delimiter );
		$csv->setHeaderOffset( 0 );
		
		$headers = $csv->getHeader();
		self::checkHeaders( $headers );
		
		return Statement::create()->process($csv, $headers);
	}
	
	/**
	 * @param TabularDataReader $reader
	 *
	 * @return int[]
	 */
	#[ArrayShape( [
		'skippedItems' => "int",
		'newItems'     => "int",
		'updatedItems' => "int",
		'invalidItems' => "int"
	] )]
	private function importData( TabularDataReader $reader ): array {
		$skippedItems = 0;
		$newItems     = 0;
		$updatedItems = 0;
		$invalidItems = 0;
		
		foreach ($reader->getRecords() as $key => $record) {
			if ( $record[ self::$headers['cost'] ] > 1000 ) {
				$skippedItems ++;
				continue;
			}
			if ( $record[ self::$headers['cost'] ] < 5 && $record[ self::$headers['stock'] ] < 10 ) {
				$skippedItems ++;
				continue;
			}
			
			var_dump( $record );
			//todo create entity
			
			if ($key % 10 == 0) {
				//todo save to db
			}
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
}
