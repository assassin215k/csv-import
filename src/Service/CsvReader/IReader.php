<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 05.01.22
 * Time: 19:00
 */

namespace App\Service\CsvReader;

use Iterator;

/**
 * IReader
 */
interface IReader
{
    public function init(string $fileName, string $delimiter): int;

    public function read(int $page): Iterator;
}
