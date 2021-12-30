<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 22:00.
 */

namespace App\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * WrongCsvHeadersException.
 */
class ReadNotInitializedException extends Exception
{
    protected $message = 'Reader is not initialized. User init method';

    /**
     * Exception constructor.
     */
    #[Pure]
    public function __construct()
    {
        parent::__construct($this->message);
    }
}
