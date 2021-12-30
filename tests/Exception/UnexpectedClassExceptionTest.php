<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 13:33.
 */

namespace App\Tests\Exception;

use App\Exception\UnexpectedClassException;
use PHPUnit\Framework\TestCase;

/**
 * UnexpectedClassExceptionTest.
 */
class UnexpectedClassExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct()
    {
        $exception = new UnexpectedClassException(UnexpectedClassExceptionTest::class);

        $this->assertSame("Unexpected class, required 'App\Tests\Exception\UnexpectedClassExceptionTest'.", $exception->getMessage());
    }
}
