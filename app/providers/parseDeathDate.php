<?php
namespace app\providers{
class parseDeathDate extends \app\textParser{
	public function __construct($text){
		$this->text = $text;
		$this->onController();
	}
	
	public function parseLevel1(){
		$pos1 = strpos($this->text,"Death:")+strlen("Death:");
		//$pos2 = strpos($this->text,"Date", $pos1);
		//$pos2 = $this->nextWord($this->text,$pos1+2);
		$string = substr($this->text,$pos1,11);
		$string = trim($string);
		//echo "This is string-->". $string;
		$D = new parseDate($string);
		$r = $D->parseNoSpace();
		//echo "<br>This is parse no space--->" . $r;
		return $r;
	}
	
	public function testLevel1(){
		if(strlen($this->result) == 10){
			return 1;
		}else{
			return -1;
		}
	}
	
	public function parseLevel2(){
		$D = new parseDate($this->result);
		return $D->findEnd($this->result);
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