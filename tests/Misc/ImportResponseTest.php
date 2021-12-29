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

        $response = (string) $response;

        $this->assertStringContainsString('Success: 1', $response);
        $this->assertStringContainsString('Skipped line numbers: 3,4', $response);
        $this->assertStringContainsString('Invalid codes: 001', $response);
        $this->assertStringContainsString('Total records proceed: 4', $response);
    }
}
