<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 13:35.
 */

namespace App\Tests\Exception;

use App\Exception\WrongCsvHeadersException;
use PHPUnit\Framework\TestCase;

/**
 * WrongCsvHeadersExceptionTest.
 */
class WrongCsvHeadersExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct()
    {
        $exception = new WrongCsvHeadersException('someFile');

        $this->assertSame("Headers didn't match!", $exception->getMessage());
    }
}
