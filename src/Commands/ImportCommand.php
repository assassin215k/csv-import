<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 13:00
 */

namespace App\Commands;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command {
	
	protected static $defaultName = 'app:csv-import';
	protected static $defaultDescription = 'Import products from provided csv file';
	
	protected function configure(): void {
		$this
			->addArgument( 'file', InputArgument::REQUIRED, 'The file name to proceed' )
			->addOption( 'delimiter',
			             null,
			             InputOption::VALUE_OPTIONAL,
			             'The delimiter in strings, by default is comma(,)',
			             ',' )
			->setHelp( "This command allows you to import products from the local csv file" );
	}
	
	/**
	 * @throws Exception if file is invalid
	 */
	protected function execute( InputInterface $input, OutputInterface $output ): int {
		$output->writeln( "Start" );
		
		$fileName  = $input->getArgument( 'file' );
		$delimiter = $input->getOption( 'delimiter' );
		
		$this->checkFile( $fileName );
		
		$output->writeln( "File: $fileName" );
		
		return Command::SUCCESS;
	}
	
	/**
	 * @throws Exception
	 */
	private function checkFile( string $fileName ): void {
		if ( ! is_readable( $fileName ) ) {
			throw new Exception( "File '$fileName' doesn't found or unavailable" );
		}
		
		if ( ! filesize( $fileName ) ) {
			throw new Exception( "File '$fileName' is empty!" );
		}
	}
}
