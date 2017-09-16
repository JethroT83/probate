<?php
class parseDeceasedAddress extends textParser{
	public function __construct($text, $zip){
		$this->zip = $zip;
		$this->text = $text;
		$this->onController();
	}
	
	function parseLevel1(){
		$pos1 = strpos($this->text,"Address")+strlen("Address:");
		$pos2 = strpos($this->text,"Age:",$pos1);
		$this->r = substr($this->text,$pos1,$pos2-$pos1);
		
		//echo "Print Address--->";
		//print_r($this->r);
		
		$AD = new addressParser($this->r, $this->zip);
		
		$this->result['street'] = $AD->streetFinderLevel1();
		$this->result['city'] 	= $AD->cityFinderLevel1();
		$this->result['state'] 	= $AD->stateFinderLevel1();
		$this->result['zip'] 	= $AD->zipFinderLevel1();
		
	}
	
	public function testLevel1(){
		foreach($this->result as $i => $test){
			if($test == -1){
				return -1;
			}
		}
		return 1;
	}
	
	public function parseLevel2(){
		$AD = new addressParser($this->r, $this->zip);
		if($this->result['zip'] != -1 and $this->result['city'] == -1){
			$this->result['city'] = $AD->cityFinderLevel2($this->result['zip']);
		}
		
		if($this->result['city'] != -1 and $this->result['street'] == -1){
			$this->result['street'] = $AD->streetFinderLevel2($this->result['city']);
		}
	
	}
	
	public function onController(){
		$this->parseLevel1();
		if($this->testLevel1() == -1){
			$this->parseLevel2();
		}
	}

}