<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 17.12.21
 * Time: 08:44
 */

namespace App\Misc;

/**
 * Class to description csv file header
 */
class CsvRow
{

    public const CODE = "Product Code";
    public const NAME = "Product Name";
    public const DESC = "Product Description";
    public const STOCK = "Stock";
    public const COST = "Cost in GBP";
    public const DISC = "Discontinued";

    public static array $headers = [
        self::CODE,
        self::NAME,
        self::DESC,
        self::STOCK,
        self::COST,
        self::DISC,
    ];
}
