<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 20.12.21
 * Time: 15:30
 */

namespace App\Validator;

use App\Entity\Product;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidPriceValidator extends ConstraintValidator {
	
	public function validate( $value, Constraint $constraint ) {
		if ( ! $constraint instanceof ValidPrice ) {
			throw new UnexpectedTypeException( $constraint, ValidPrice::class );
		}
		
		// custom constraints should ignore null and empty values to allow
		// other constraints (NotBlank, NotNull, etc.) to take care of that
		if ( null === $value || '' === $value ) {
			return;
		}
		
		if ( ! is_float( $value ) ) {
			// throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
			throw new UnexpectedValueException( $value, 'float' );
		}
		
		if ( $value > 1000 ) {
			// the argument must be a string or an object implementing __toString()
			$this->context->buildViolation( $constraint->message )
			              ->setParameter( '{{ string }}', $value )
			              ->addViolation();
		}
	}
}
