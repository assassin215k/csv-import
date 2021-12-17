<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 17.12.21
 * Time: 12:02
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class ValidStock extends Constraint {
	
	public $message = 'Empty stock or not enough stock of the cheap product ';
}
