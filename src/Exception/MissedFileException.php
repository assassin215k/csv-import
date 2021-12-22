<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 22:10
 */

namespace App\Exception;

use Exception;

class MissedFileException extends Exception {
	
	protected $message = "File '{fileName}' doesn't found or unavailable!";
	
	public function __construct( string $fileName ) {
		parent::__construct( str_replace( '{fileName}', $fileName, $this->message ) );
	}
}
