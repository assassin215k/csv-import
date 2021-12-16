<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 16.12.21
 * Time: 08:30
 */

namespace App\Service;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator {
	
	public function isValidRow(array $row, ValidatorInterface $validator):bool
	{
		
		
		$emailConstraint = new Assert\Count();
		// all constraint "options" can be set this way
		$emailConstraint->message = 'Invalid email address';
		
		// use the validator to validate the value
		$errors = $validator->validate(
			$row,
			[$emailConstraint]
		);
		
		if (!$errors->count()) {
			// ... this IS a valid email address, do something
		} else {
			// this is *not* a valid email address
			$errorMessage = $errors[0]->getMessage();
			
			// ... do something with the error
		}
		
		// ...
	}
}
