<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 17.12.21
 * Time: 12:02
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class ValidPrice extends Constraint {
	
	public $message = 'Price must be positive and no more than 1000.';
}
