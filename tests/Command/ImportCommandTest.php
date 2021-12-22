<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 22.12.21
 * Time: 9:04
 */

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCommandTest extends KernelTestCase {
	
	public function testExecute() {
		$kernel      = self::bootKernel();
		$application = new Application( $kernel );
		
		$command       = $application->find( 'app:csv-import' );
		$commandTester = new CommandTester( $command );
		$commandTester->execute( [ 'file' => './.info/stock.csv' ] );
		
		$commandTester->assertCommandIsSuccessful();
		
		// the output of the command in the console
		$output = $commandTester->getDisplay();
		$this->assertStringContainsString( 'File: ./.info/stock.csv', $output );
	}
}
