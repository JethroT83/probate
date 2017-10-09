<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetupService as Setup;

class E_parseDecFullNameTest extends TestCase{

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
		self::$field = "DecdFullNamePulled";

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

		$test = true; // Boolean 
		$tValue = ""; // Failed test value
		$cValue = ""; // Failed control value
		$failedDocket = false; // Failed Docket number
		foreach(self::$control as $docket => $control){
			$test = true;

			// Fail the test of the control is not equal to the test
			if( 	!isset($csv[$docket]) 
				|| 	$csv[$docket][self::$field] != $control[self::$field]){
				$test = false;
				$failedDocket = $docket;
				$cValue = self::$control[$docket][self::$field];
				$tValue = @$csv[$docket][self::$field]; //error is suppress to see where test fails
				break;
			}
		}

		$this->assertTrue($test,"Test failed with docket: ".$failedDocket." control-->" . $cValue . "--test-->".$tValue);
	}

}

?>