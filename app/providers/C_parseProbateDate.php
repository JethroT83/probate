<?php
namespace app\providers{
class C_parseProbateDate extends \app\textParser{

	public function __construct($text){
		$this->text = $text;
		$this->onController();
		return $this->result;
	}
	
	function parseLevel1(){
		$pos1 = strpos($this->text,"File Date:")+strlen("File Date:");
		$pos2 = strpos($this->text,"Date of Death:",$pos1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
			return trim($string);
	}
	
	
	public function testLevel1(){
		$D = new parseDate($this->result);
		$testSpace =  $D->testSpace();
	}
	
	public function parseLevel2(){
		$D = new parseDate($this->result);
		$result = $D->parseNoSpace();
		return $D->findEnd($result);
		
	}
	
	public function onController(){
		$this->result = $this->parseLevel1();
		$test = $this->testLevel1();
		if($test == -1){
			$this->result = $this->parseLevel2();
		}
	}
}
}