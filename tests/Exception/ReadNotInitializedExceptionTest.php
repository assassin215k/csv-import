<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 13:35.
 */

namespace App\Tests\Exception;

use App\Exception\ReadNotInitializedException;
use PHPUnit\Framework\TestCase;

/**
 * WrongCsvHeadersExceptionTest.
 */
class ReadNotInitializedExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct()
    {
        $exception = new ReadNotInitializedException();

        $this->assertSame('Reader is not initialized. User init method', $exception->getMessage());
    }
}
