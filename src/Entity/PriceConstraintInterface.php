<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 27.12.21
 * Time: 13:11.
 */

namespace App\Entity;

/**
 * CustomConstraintInterface.
 */
interface PriceConstraintInterface
{
    public function getCost(): float;

    public function getStock(): int;
}
