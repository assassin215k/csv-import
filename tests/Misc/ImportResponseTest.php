<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 29.12.21
 * Time: 12:06
 */

namespace App\Tests\Misc;

use App\Misc\ImportResponse;
use PHPUnit\Framework\TestCase;

/**
 * ImportResponseTest
 */
class ImportResponseTest extends TestCase
{
    /**
     * @return void
     */
    public function testTotal()
    {
        $response = new ImportResponse();

        $response->successItems++;
        $response->invalidCode[] = '001';
        $response->skippedString[] = 3;
        $response->skippedString[] = 4;

        $this->assertEquals(4, $response->total());
        $this->assertSame("=====\r\nSuccess: 1\r\nSkipped line numbers: 3,4\r\nInvalid codes: 001\r\n=====\r\nTotal records proceed: 4\r\n=====\r\n", (string) $response);
    }
}
