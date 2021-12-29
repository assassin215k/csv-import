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
interface PriceConstraintInterface
{
    /**
     * @return float
     */
    public function getCost(): float;

    /**
     * @return int
     */
    public function getStock(): int;
}
