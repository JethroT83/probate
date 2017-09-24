<?php

use \test\serviceSetup as setup;
class E_parseDevFullNameTest extends PHPUnit_Framework_TestCase{

	private static $control;
	private static $field;

	protected function setUp(){

		# Basic Unit Setup
		setup::setUpUnit();

		# Copies the data set to the storage Folder
		setup::copy_files();

		# Get the Controled Variables form the dataSet folder
		self::$control = setup::getControl();

		# Sets the field name that will be tested
		self::$field = "DecdFullNamePulled";

	}


	protected function tearDown(){
		
		# Deletes the files in the storage folder
		setup::unlink_files();
	}


	public function testCode(){

		require("index.php");

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