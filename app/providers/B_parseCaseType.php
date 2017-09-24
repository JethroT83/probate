<?php
namespace app\providers{
Class B_parseCaseType extends \app\textParser{

	public function __construct($text){
		$this->caseTypes = array("PROBATE","NEXT OF KIN AFFIDAVIT", "SPOUSE AFFIDAVIT");
		$this->text = $text;
		$this->onController();
		return $this->result;
	}
	
	
	public function parseLevel1(){
		$pos1 = strpos($this->text,"Case Type:")+strlen("Case Type:");
		$pos2 = strpos($this->text,"DocketNumber:");
		//$pos2 = $this->nextWord($this->text,$pos1+1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
		$string = strtoupper($string);
		
		foreach($this->caseTypes as $i => $type){
			$type =  strtoupper($type);
			if(stripos($string, $type) !== false){
				return $type;
			}
		}
		return trim($string);
	}
	
	public function testLevel1(){
		if(array_search($this->result,$this->caseTypes)!==false){
			return 1;
		}else{
			return -1;
		}
	}
	
	public function parseLevel2(){
		#$f = fopen("caseTypes_NotFound.txt","w");
		#fwrite($f,$this->result);
		#fclose($f);
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