<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 13:58
 */

namespace App\Tests\Validator;

use App\Entity\Product;
use App\Validator\ValidPriceValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

class ValidPriceValidatorTest extends TestCase {
	
	private static ?ValidPriceValidator $object;
	
	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		
		static::$object = new ValidPriceValidator();
	}
	
	public static function tearDownAfterClass(): void {
		parent::tearDownAfterClass();
		
		static::$object = null;
	}
	
	protected function setUp(): void {
	}
	
	protected function tearDown(): void {
	}
	
//	public function __construct() {
//		parent::__construct();
//	}
	
	public function testWrongEntity() {
		$product = new Product();
//		$this->object->validate( new \DateTime(), $this->constraint );
		//		$this->ex();
	}
	
	public function testMath() {
		$this->assertEquals( 4, 2 + 2 );
	}
	
	//	public function __construct() {
	//		parent::__construct();
	//
	//		$this->object = new ValidPriceValidator();
	//	}
	
	//	public function testValidate() {
	//		$test = 'username';
	//
	//		$this->assertSame( $test, $this->object->validateUsername( $test ) );
	//	}
	//
	//	public function testValidateUsernameEmpty() {
	//		$this->expectException( 'Exception' );
	//		$this->expectExceptionMessage( 'The username can not be empty.' );
	//		$this->object->validateUsername( null );
	//	}
	//
	//	public function testValidateUsernameInvalid() {
	//		$this->expectException( 'Exception' );
	//		$this->expectExceptionMessage( 'The username must contain only lowercase latin characters and underscores.' );
	//		$this->object->validateUsername( 'INVALID' );
	//	}
	//
	//	public function testValidatePassword() {
	//		$test = 'password';
	//
	//		$this->assertSame( $test, $this->object->validatePassword( $test ) );
	//	}
	//
	//	public function testValidatePasswordEmpty() {
	//		$this->expectException( 'Exception' );
	//		$this->expectExceptionMessage( 'The password can not be empty.' );
	//		$this->object->validatePassword( null );
	//	}
	//
	//	public function testValidatePasswordInvalid() {
	//		$this->expectException( 'Exception' );
	//		$this->expectExceptionMessage( 'The password must be at least 6 characters long.' );
	//		$this->object->validatePassword( '12345' );
	//	}
	//
	//	public function testValidateEmail() {
	//		$test = '@';
	//
	//		$this->assertSame( $test, $this->object->validateEmail( $test ) );
	//	}
	//
	//	public function testValidateEmailEmpty() {
	//		$this->expectException( 'Exception' );
	//		$this->expectExceptionMessage( 'The email can not be empty.' );
	//		$this->object->validateEmail( null );
	//	}
	//
	//	public function testValidateEmailInvalid() {
	//		$this->expectException( 'Exception' );
	//		$this->expectExceptionMessage( 'The email should look like a real email.' );
	//		$this->object->validateEmail( 'invalid' );
	//	}
	//
	//	public function testValidateFullName() {
	//		$test = 'Full Name';
	//
	//		$this->assertSame( $test, $this->object->validateFullName( $test ) );
	//	}
	//
	//	public function testValidateFullNameEmpty() {
	//		$this->expectException( 'Exception' );
	//		$this->expectExceptionMessage( 'The full name can not be empty.' );
	//		$this->object->validateFullName( null );
	//	}
}
