<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 16.12.21
 * Time: 08:30
 */

namespace App\Service;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator {
	
	private readonly ValidatorInterface $validator;
	
	public function __Construct() {
		$this->validator = Validation::createValidator();
	}
	
	public function isValidRow(array $row, ValidatorInterface $validator):bool
	{
		$countConstraint = new Assert\Count();
		// all constraint "options" can be set this way
		$countConstraint->message = 'Field count is invalid';
		
		// use the validator to validate the value
		$errors = $this->validator->validate(
			$row,
			[$countConstraint]
		);
		
		if ($errors->count()) {
			return false;
		}
		
		return true;
	}
	
	private function isValidName(array $row) {
	
	}
}
