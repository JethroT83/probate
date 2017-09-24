<?php
namespace app\providers{
class E_parseDeceasedName extends \app\textParser{

	public function __construct($text){
		$this->text = $text;
		$this->onController();
		return $this->result;
	}
	
	public function parseLevel1(){
		$pos1 = strpos($this->text,"Name:")+strlen("Name:");
		$pos2 = strpos($this->text,"Address",$pos1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
		return trim($string);
	}
	
	public function testLevel1(){
		/*$p = new parseDeceasedName($this->result);
		$spaces = $p->countSpaces();
		if($space == 1){
			return 1;
		else{
			return -1;
		}*/
		return 1;
	}
	
	public function parseLevel2(){
		/*$p = new parseDeceasedName($this->result);
		$spaces = $p->countSpaces();
		if(*/
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
?>