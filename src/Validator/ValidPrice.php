<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 21.12.21
 * Time: 18:24
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for price
 */
class ValidPrice extends Constraint
{

    public string $message = 'If the product cost less then 5 that store must be more then 10';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return static::class.'Validator';
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
