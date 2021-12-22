<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 21.12.21
 * Time: 18:29
 */

namespace App\Validator;

use App\Entity\Product;
use App\Exception\UnexpectedClassException;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidPriceValidator extends ConstraintValidator {
	
	/**
	 * @throws Exception
	 */
	public function validate( $value, Constraint $constraint ) {
		
		if ( ! $value instanceof Product ) {
			throw new UnexpectedClassException( Product::class );
		}
		
		if ( ! $constraint instanceof ValidPrice ) {
			throw new UnexpectedTypeException( $constraint, ValidPrice::class );
		}
		
		if ( $value->getCost() < 5 && $value->getStock() < 10 ) {
			$this->context->buildViolation( $constraint->message )->addViolation();
		}
	}
}
