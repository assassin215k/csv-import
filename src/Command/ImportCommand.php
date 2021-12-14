<?php
/**
 * Created by PhpStorm.
 * Author: Ihor Fedan
 * Date: 14.12.21
 * Time: 13:00
 */

namespace App\Command;

use App\Service\Importer;
use Exception;
use League\Csv\InvalidArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command {
	
	protected static $defaultName = 'app:csv-import';
	protected static $defaultDescription = 'Import products from provided csv file';
	
	public function __construct( public Importer $service ) {
		parent::__construct();
	}
	
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
		
		try {
			list( $skippedItems, $newItems, $updatedItems, $invalidItems ) = $this
				->service
				->import(
					$input->getArgument( 'file' ),
					$input->getOption( 'delimiter' )
				);
			
			$output->writeln( "Done:" );
			$output->writeln( "Skipped: $skippedItems" );
			$output->writeln( "New: $newItems" );
			$output->writeln( "Updated: $updatedItems" );
			$output->writeln( "Invalid: $invalidItems" );
			
			return Command::SUCCESS;
		} catch ( InvalidArgument $e ) {
			$output->writeln( "Invalid delimiter specified!" );
			
			return Command::INVALID;
		} catch ( \League\Csv\Exception $e ) {
			$output->writeln( $e->getMessage() );
		} catch ( Exception $e ) {
			switch ( $e->getCode() ) {
				case 1:
				case 2:
					$output->writeln( $e->getMessage() );
					
					return Command::INVALID;
				case 3:
					$output->writeln( $e->getMessage() );
					break;
			}
		}
		
		return Command::FAILURE;
	}
}
