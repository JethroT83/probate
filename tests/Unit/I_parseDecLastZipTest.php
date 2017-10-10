<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetupService as Setup;

class I_parseDecLastZipTest extends TestCase{

	private static $control;
	private static $field;

	protected function setUp(){

		# Larael
		parent::setup();

		# Basic Unit Setup
		setup::setUpUnit();

		# Copies the data set to the storage Folder
		setup::copy_files();

		# Get the Controled Variables form the dataSet folder
		self::$control = setup::getControl();

		# Sets the field name that will be tested
		self::$field = "DecdLastZip";

	}


	protected function tearDown(){
		
		# Deletes the files in the storage folder
		setup::unlink_files();
	}


	public function testCode(){

		$R = new \App\Http\Controllers\RunController();
		$R->handle();
		
		# Get the test data
		$csv = setup::getTest();

		$tValue = ""; // Failed test value
		$cValue = ""; // Failed control value
		$exceptions = array();
		foreach(self::$control as $docket => $control){

			// Fail the test of the control is not equal to the test
			if( 	!isset($csv[$docket]) 
				|| 	$csv[$docket][self::$field] != $control[self::$field]){
				array_push($exceptions,$docket);
				$cValue[$docket] = self::$control[$docket][self::$field];
				$tValue[$docket] = @$csv[$docket][self::$field]; //error is suppress to see where test fails
			}
		}

		$test 		= Setup::grade($exceptions);
		$message 	= Setup::getMessage($exceptions,$cValue,$tValue);

		$this->assertTrue($test,$message);
	}

}

?>