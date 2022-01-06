<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 17.12.21
 * Time: 08:44.
 */

namespace App\Enum;

/**
 * Class to description csv file header.
 */
enum Row: string
{
    public const CODE = 'Product Code';
    public const NAME = 'Product Name';
    public const DESC = 'Product Description';
    public const STOCK = 'Stock';
    public const COST = 'Cost in GBP';
    public const DISC = 'Discontinued';

    public static function getHeaders(): array
    {
        return [
            self::CODE,
            self::NAME,
            self::DESC,
            self::STOCK,
            self::COST,
            self::DISC,
        ];
    }
}
