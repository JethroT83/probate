<?php

Class parseProbateName extends textParser{
	
	public function __construct($text, $stop = "Exe"){
		$this->text = $text;
		$this->stop = $stop;
		$this->onController();
	}
	
	public function parseLevel1(){
		$pos = strpos($this->text,"Relation")+strlen("Relation");
		$pos1 = $this->strposProbate($this->text,"Relation")+strlen("Relation");
		//echo "<br>" . $pos . " ---- " , $pos1;
		$pos2 = strpos($this->text,$this->stop,$pos1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
		
		for($x=0;$x<=strlen($string);$x++){
			$l = substr($string, $x, 1);
			echo ctype_upper($l);
			if(ctype_upper($l)==true){
				$start = $x;
				break;
			}
		}
		 $string = substr($string,$start);
		
		return trim($string);
	}
	
	public function testLevel1(){

	}
	
	public function parseLevel2(){

	}
	
	
	public function onController(){
		$this->result = $this->parseLevel1();
		/*$test = $this->testLevel1();
		if($test == -1){
			$this->result = $this->parseLevel2();
		}*/
	}
}
?>