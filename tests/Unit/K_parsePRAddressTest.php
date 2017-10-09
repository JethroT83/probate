<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetupService as Setup;

class K_parsePRAddressTest extends TestCase{

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
		self::$field = "PRAddress";

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