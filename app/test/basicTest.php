<?php

class BasicTest extends PHPUnit_Framework_TestCase{

	private static $control;

	protected function setUp(){

		#$t = getenv("t");
		$t = 1;
		$dir  = __DIR__."/record_".$t;
echo getcwd();
		copy($dir."/build/build.pdf","build.pdf");

		self::$control = $dir."/control/control.json"; 
	}

	protected function tearDown(){

		#unlink("build.pdf");

	}


	public function testCode(){

		require_once("parseRecords.php");

		#$file = "build.pdf";
		#$P =  new parse($file);
		#$this->assertTrue(1==1,"test");
	}

}

?>