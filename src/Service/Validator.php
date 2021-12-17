<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 16.12.21
 * Time: 08:30
 */

namespace App\Service;

use App\Misc\CsvRow;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validator class to validate scv row before write to DB
 */
class Validator {
	
	private readonly ValidatorInterface $validator;
	
	public function __Construct() {
		$this->validator = Validation::createValidator();
	}
	
	public function validateRow(array $row):bool {
		if (!$this->isValidRow($row)) {
			return false;
		}
		if (!$this->isValidName($row)) {
			return false;
		}
		
		if ( $row[ CsvRow::$headers['cost'] ] > 1000 ) {
			return false;
		}
		if ( $row[ CsvRow::$headers['cost'] ] < 5 && $row[ CsvRow::$headers['stock'] ] < 10 ) {
			return false;
		}
		
		return true;
	}
	
	private function isValidRow(array $row):bool
	{
		$countConstraint = new Assert\Count(exactly: 6);
		$countConstraint->exactMessage = 'Row length is invalid, shout be 6 fields';
		
		$errors = $this->validator->validate(
			$row,
			$countConstraint
		);
		
		return $errors->count();
	}
	
	private function isValidName(array $row):bool {
		return true;
	}
}
