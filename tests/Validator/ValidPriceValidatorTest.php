<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 13:58
 */

namespace App\Tests\Validator;

use App\Entity\Product;
use App\Exception\UnexpectedClassException;
use App\Validator\ValidPrice;
use App\Validator\ValidPriceValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

class ValidPriceValidatorTest extends TestCase {
//
//	private static ?ValidPriceValidator $validator;
//	private static ?ValidPrice $constraint;
//
//	public static function setUpBeforeClass(): void {
//		parent::setUpBeforeClass();
//
//		static::$validator  = new ValidPriceValidator();
//		static::$constraint = new ValidPrice();
//	}
//
//	public static function tearDownAfterClass(): void {
//		parent::tearDownAfterClass();
//
//		static::$validator  = null;
//		static::$constraint = null;
//	}
//
//	public function testWrongEntityClass() {
//		$context = $this->getMockExecutionContext();
//		$context->expects( $this->once() )
//		        ->method( 'buildViolation' )
//		        ->with( static::$constraint->message )
//		        ->willReturn( $this->getMockConstraintViolationBuilder() );
//
//		static::$validator->initialize( $context );
//
//		$this->expectException( UnexpectedClassException::class );
//
//		static::$validator->validate( new \DateTime(), static::$constraint );
//	}
//
//	public function testWrongEntityString() {
//		$context = $this->getMockExecutionContext();
//		$context->expects( $this->once() )
//		        ->method( 'buildViolation' )
//		        ->with( static::$constraint->message )
//		        ->willReturn( $this->getMockConstraintViolationBuilder() );
//
//		static::$validator->initialize( $context );
//
//		$this->expectException( UnexpectedClassException::class );
//
//		static::$validator->validate( "text", static::$constraint );
//	}
//
//	public function testWrongEntityArray() {
//		$context = $this->getMockExecutionContext();
//		$context->expects( $this->once() )
//		        ->method( 'buildViolation' )
//		        ->with( static::$constraint->message )
//		        ->willReturn( $this->getMockConstraintViolationBuilder() );
//
//		static::$validator->initialize( $context );
//
//		$this->expectException( UnexpectedClassException::class );
//
//		static::$validator->validate( [], static::$constraint );
//	}
//
//	public function testInvalidItems( $text ) {
//	}
//
//	protected function setUp(): void {
//	}
//
//	protected function tearDown(): void {
//	}
//
//	/**
//	 * @return MockObject
//	 */
//	private function getMockExecutionContext(): MockObject {
//		return $this
//			->getMockBuilder( 'Symfony\Component\Validator\ExecutionContext' )
//			->disableOriginalConstructor()
//			->addMethods( [ 'buildViolation' ] )
//			->getMock();
//	}
//
//	/**
//	 * @return MockObject
//	 */
//	private function getMockConstraintViolationBuilder(): MockObject {
//		$constraintViolationBuilder = $this
//			->getMockBuilder( 'Symfony\Component\Validator\Violation\ConstraintViolationBuilder' )
//			->disableOriginalConstructor()
//			->getMock();
//
//		$constraintViolationBuilder->method( 'setParameter' )->willReturn( $constraintViolationBuilder );
//
//		$constraintViolationBuilder->method( 'addViolation' );
//
//		return $constraintViolationBuilder;
//	}
}
