<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 22:10.
 */

namespace App\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * MissedFileException.
 */
class MissedFileException extends Exception
{
    protected $message = "File '%s' doesn't found or unavailable!";

    /**
     * @param string $fileName
     */
    #[Pure]
    public function __construct(string $fileName)
    {
        parent::__construct(empty($fileName) ?: sprintf($this->message, $fileName));
    }
}
