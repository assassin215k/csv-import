<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 29.12.21
 * Time: 12:20.
 */

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * ImportCommandTest.
 */
class ImportCommandTest extends KernelTestCase
{
    private CommandTester $tester;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $command = $application->find('app:csv-import');
        $this->tester = new CommandTester($command);
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->tester->execute([
            'file' => 'tests/csvForTests/stock.csv',
        ]);

        $this->tester->assertCommandIsSuccessful();

        $output = $this->tester->getDisplay();

        $this->assertStringContainsString('Success: 19', $output);
        $this->assertStringContainsString('Skipped line numbers: 34', $output);
        $this->assertStringContainsString('Total records proceed: 35', $output);
    }

    /**
     * @return void
     */
    public function testExecuteInvalid()
    {
        $this->tester->execute(
            [
                'file' => 'tests/csvForTests/stock.csv',
                '--delimiter' => 'as',
            ],
        );

        $this->tester->assertCommandIsSuccessful();

        $output = $this->tester->getDisplay();
        $this->assertStringContainsString('Invalid delimiter specified', $output);
    }

    /**
     * @return void
     */
    public function testWrongFile()
    {
        $this->tester->execute(
            [
                'file' => '/stock.csv',
            ],
        );

        $this->tester->assertCommandIsSuccessful();

        $output = $this->tester->getDisplay();
        $this->assertStringContainsString("File '/stock.csv' doesn't found or unavailable!", $output);
    }
}
