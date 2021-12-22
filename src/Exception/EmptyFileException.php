<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 22:04
 */

namespace App\Exception;

use Exception;

class EmptyFileException extends Exception {
	
	protected $message = "File '{fileName}' is empty!";
	
	public function __construct( string $fileName ) {
		parent::__construct( str_replace( '{fileName}', $fileName, $this->message ) );
	}
}
