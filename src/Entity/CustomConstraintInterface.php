<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 27.12.21
 * Time: 13:11
 */

namespace App\Entity;

/**
 * CustomConstraintInterface
 */
interface CustomConstraintInterface
{
    /**
     * @return bool
     */
    public function isInvalid(): bool;

    /**
     * @return string
     */
    public function getInvalidMessage(): string;
}
