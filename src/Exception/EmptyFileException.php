<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 22:04
 */

namespace App\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * EmptyFileException
 */
class EmptyFileException extends Exception
{

    protected $message = "File '%s' is empty!";

    /**
     * @param string $fileName
     */
    #[Pure]
    public function __construct(string $fileName)
    {
        parent::__construct(empty($fileName) ?: sprintf($this->message, $fileName));
    }
}
