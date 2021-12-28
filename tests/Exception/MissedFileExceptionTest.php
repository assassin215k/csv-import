<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 28.12.21
 * Time: 13:32
 */

namespace App\Tests\Exception;

use App\Exception\MissedFileException;
use PHPUnit\Framework\TestCase;

/**
 * MissedFileExceptionTest
 */
class MissedFileExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct()
    {
        $exception = new MissedFileException('someFile');

        $this->assertSame("File 'someFile' doesn't found or unavailable!", $exception->getMessage());
    }
}
