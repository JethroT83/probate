<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetupService as Setup;

class B_parseCaseTypeTest extends TestCase{

	private static $control;
	private static $field;

	protected function setUp(){

		# Larael
		parent::setup();

		# Basic Unit Setup
		Setup::setUpUnit();

		# Copies the data set to the storage Folder
		Setup::copy_files();

		# Get the Controled Variables form the dataSet folder
		self::$control = Setup::getControl();

		# Sets the field name that will be tested
		self::$field = "CaseType";

	}


	protected function tearDown(){
		
		# Deletes the files in the storage folder
		Setup::unlink_files();
	}


	public function testCode(){

		$R = new \App\Http\Controllers\RunController();
		$R->handle();

		# Get the test data
		$csv = Setup::getTest();

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