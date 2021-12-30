<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 13:27.
 */

namespace App\Tests\Exception;

use App\Exception\EmptyFileException;
use PHPUnit\Framework\TestCase;

/**
 * EmptyFileExceptionTest.
 */
class EmptyFileExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct()
    {
        $exception = new EmptyFileException('someFile');

        $this->assertSame("File 'someFile' is empty!", $exception->getMessage());
    }
}
