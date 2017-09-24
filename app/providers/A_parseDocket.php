<?php
namespace app\providers{
Class A_parseDocket extends \app\textParser{

	public function __construct($text){
		$this->text = $text;
		$this->onController();
		return $this->result;
	}
	
	function parseLevel1(){
		$pos1 = strpos($this->text,"Docket Number")+15;
		//$pos2 = strpos($this->text,"Name:",$pos1);
		$string = substr($this->text,$pos1,10);
		$d = "";
		for($x=0;$x<=strlen($string);$x++){
			$l = substr($string, $x,1);
			if(is_numeric($l)){
				$d .= $l;
			}
		}
		
		return trim($d);
	}
	
	function testLevel1(){
		$array = array(0,1,3,4,5,6,7,8,9,'0','1','2','3','4','5','6','7','8','9');
		$length = strlen($this->result);
		for($x=0;$x<=$length;$x++){
			$l = substr($this->result,$x,1);
			//if(array_search($l,$array)==false){
			if(!is_numeric($l)){
				return -1;
			}
		}
		return 1;
	}
	
	function parseLevel2(){
		$string = "";
		for($x=0;$x<=strlen($this->result);$x++){
			if(is_numeric(substr($this->result,$x))){
				$string .= substr($this->result,$x,1);
				if(strlen($string) == 7){break;}
			}
		}
		return $string;
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
