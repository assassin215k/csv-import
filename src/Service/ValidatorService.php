<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 16.12.21
 * Time: 08:30
 */

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validator class to validate scv row before write to DB
 */
class ValidatorService {
	
	private readonly ValidatorInterface $validator;
	
	public function __Construct() {
		$this->validator = Validation::createValidator();
	}
	
	public function isValidProduct( $product ): bool {
		$errors = $this->validator->validate( $product );
		
		var_dump((string) $errors);
		
		if ( count( $errors ) > 0 ) {
			echo "Invalid product: '$product' $errors";
			
			return false;
		}
		
		return true;
	}
}
