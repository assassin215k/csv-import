<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 16:45
 */

namespace App\Service;

use App\Exception\EmptyFileException;
use App\Exception\MissedFileException;
use App\Exception\WrongCsvHeadersException;
use Exception;
use League\Csv\CharsetConverter;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\TabularDataReader;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * CsvReaderService to read csv file and check headers to be identical with target
 */
class CsvReaderService {
	
	/**
	 * @param string $fileName
	 * @param string $delimiter
	 * @param array  $targetHeaders
	 *
	 * @return TabularDataReader
	 * @throws InvalidArgument
	 * @throws \League\Csv\Exception
	 * @throws MissedFileException
	 * @throws EmptyFileException
	 * @throws WrongCsvHeadersException
	 */
	public function read( string $fileName, string $delimiter, array $targetHeaders ): TabularDataReader {
		var_dump($fileName,
$delimiter,
$targetHeaders);
		die;
		
		
		self::checkFile( $fileName );
		
		$csv = Reader::createFromPath( $fileName );
		
		$input_bom = $csv->getInputBOM();
		
		if ( $input_bom === Reader::BOM_UTF16_LE || $input_bom === Reader::BOM_UTF16_BE ) {
			CharsetConverter::addTo( $csv, 'utf-16', 'utf-8' );
		}
		
		$csv->setDelimiter( $delimiter );
		$csv->setHeaderOffset( 0 );
		
		$headers = $csv->getHeader();
		
		if ( count( array_diff( $targetHeaders, $headers ) ) ) {
			throw new WrongCsvHeadersException();
		}
		
		return Statement::create()->process( $csv, $headers );
	}
	
	/**
	 * @throws MissedFileException
	 * @throws EmptyFileException
	 */
	private static function checkFile( string $fileName ): void {
		if ( ! is_readable( $fileName ) ) {
			throw new MissedFileException( $fileName );
		}
		
		if ( ! filesize( $fileName ) ) {
			throw new EmptyFileException( $fileName );
		}
	}
}
