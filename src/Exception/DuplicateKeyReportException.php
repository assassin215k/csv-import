<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 10.01.22
 * Time: 11:08
 */

namespace App\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * DuplicateKeyReportException
 */
class DuplicateKeyReportException extends Exception
{
    protected $message = "Report with this key is already exist";

    /**
     * Exception constructor.
     */
    #[Pure]
    public function __construct()
    {
        parent::__construct($this->message);
    }
}
