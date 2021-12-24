<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 23.12.21
 * Time: 17:26
 */

namespace App\Tests\Service;

use App\Entity\Product;
use App\Exception\MissedFileException;
use App\Repository\ProductRepository;
use App\Service\CsvReaderService;
use App\Service\ImporterService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class ImporterServiceTest extends MockeryTestCase {
	
	private static ?CsvReaderService $reader;
	private static ?ValidatorService $validator;
	private EntityManagerInterface $manager;
	private ImporterService $service;
	
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		
		static::$reader    = new CsvReaderService();
		static::$validator = new ValidatorService();
	}
	
	public static function tearDownAfterClass(): void {
		parent::tearDownAfterClass();
		
		static::$reader    = null;
		static::$validator = null;
	}
	
	public function setUp(): void {
//		$repository = Mockery::mock(ProductRepository::class);
//		$repository->shouldReceive('removeWithFilterById')
//		           ->once();
//
//		$this->manager = Mockery::mock(EntityManagerInterface::class);
//		$this->manager->shouldReceive('getRepository')
//		              ->once()->with('App:Product')->andReturn($repository);
//
//		$this->manager->shouldReceive('persist')
//		              ->atLeast();
//		$this->manager->shouldReceive('flush')
//		              ->atLeast();
//
//		$this->service = new ImporterService( static::$reader, static::$validator, $this->manager );
	}
	
	public function tearDown(): void {
		unset( $this->manager );
		unset( $this->service );
	}

	public function testImportWrongPath() {
		$manager = Mockery::mock(EntityManagerInterface::class);
		
		$this->service = new ImporterService( static::$reader, static::$validator, $manager );
		
		$this->expectException(MissedFileException::class);
		
		$this->service->import('/.info/stock.csv', ',');
	}
//
//	public function testImportEmptyFile() {
//
//	}
//
//	public function testImportWrongDelimiter() {
//
//	}
}
