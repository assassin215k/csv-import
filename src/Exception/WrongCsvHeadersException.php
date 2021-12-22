<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 22:00
 */

namespace App\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

class WrongCsvHeadersException extends Exception {
	
	protected $message = "Headers didn't match!";
	
	#[Pure]
	public function __construct() {
		parent::__construct( $this->message );
	}
}
