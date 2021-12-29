<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 21.12.21
 * Time: 18:24
 */

namespace App\Validator\Product;

use Symfony\Component\Validator\Constraint;

/**
 * CustomConstraint
 */
class PriceConstraint extends Constraint
{
    public static string $message = 'If the price less then 5 that stock must be more then 10.';

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
