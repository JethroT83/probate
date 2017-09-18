<?php

class BasicTest extends PHPUnit_Framework_TestCase{

	private static $control;

	protected function setUp(){

		$t = getenv("t");
		$dir  = __DIR__."/record_".$t;

		copy($dir."/build/build.pdf","build.pdf");

		self::$control = json_decode(file_get_contents($dir."/control/control.json"),true);

	}

	protected function tearDown(){

		unlink("build.pdf");

	}


	public function testCode(){

		#require_once("index.php");

		$r = array_map('str_getcsv', file(__DIR__."/../resources/build_out.csv"));
		foreach( $r as $k => $d ) { $r[$k] = array_combine($r[0], $r[$k]); }
		$csvData = array_values(array_slice($r,1));
		foreach($csvData as $i => $row) {$csv[$row['Docket']] = $row;}

		$test = true;
		$tValue = "";
		$cValue = "";
		foreach(self::$control as $docket => $control){
			$test = true;
			foreach($control as $field => $value){
				if($csv[$docket][$field] != $value){
					$test = false;
					$failedField = $field;
					$cValue = $value;
					$tValue = $csv[$docket][$field];
					break;
				}
			}
		}

		$this->assertTrue($test,"Test failed with field " . $field .".  control-->" . $cValue . "--test-->".$tValue);
	}

}

?>