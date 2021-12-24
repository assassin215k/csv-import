<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 23.12.21
 * Time: 17:26
 */

namespace App\Tests\Service;

use App\Entity\Product;
use App\Misc\CsvRow;
use App\Repository\ProductRepository;
use App\Service\CsvReaderService;
use App\Service\ImporterService;
use App\Service\ValidatorService;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\TabularDataReader;
use PHPUnit\Framework\TestCase;

class ImporterServiceTest extends TestCase {
	
	private static CsvReaderService $reader;
	private static ValidatorService $validator;
	private EntityManagerInterface $manager;
	private ProductRepository $repository;
	private ImporterService $service;
	
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		
		static::$reader    = new CsvReaderService();
		static::$validator = new ValidatorService();
	}
	
	public function setUp(): void {
		$repository = $this
			->getMockBuilder( ProductRepository::class )
			->addMethods( [ 'removeWithFilterById' ] )
			->getMock();
		
		$repository
			->expects( $this->once() )
			->method( 'removeWithFilterById' )
			->with( [] )
			->willReturn( null );
		
		$this->manager = $this
			->getMockBuilder( EntityManagerInterface::class )
			->addMethods( [ 'getRepository', 'persist', 'flush' ] )
			->getMock();
		
		$this
			->manager
			->expects($this->once())
			->method('getRepository')
			->with('App:Product')
			->willReturn($repository);
		
		$this
			->manager
			->expects($this->atLeastOnce())
			->method('persist')
			->willReturn(new Product());
		
		$this
			->manager
			->expects($this->atLeastOnce())
			->method('flush')
			->willReturn(null);
		
		$this->service = new ImporterService( static::$reader, static::$validator, $this->manager );
	}
	
	public function tearDown(): void {
		unset( $this->reader );
		unset( $this->validator );
		unset( $this->manager );
		unset( $this->repository );
	}
	
	public function testImportWrongPath() {
	
	}
	
	public function testImportEmptyFile() {
	
	}
	
	public function testImportWrongDelimiter() {
	
	}
}
