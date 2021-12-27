<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 21:52
 */

namespace App\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * UnexpectedClassException
 */
class UnexpectedClassException extends Exception
{

    protected $message = "Unexpected class, required %s";

    /**
     * @param string $className
     */
    #[Pure]
    public function __construct(string $className)
    {
        parent::__construct(sprintf($this->message, $className));
    }
}
