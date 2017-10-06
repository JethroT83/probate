<?php
namespace App\Core; 
	
use \App\Core\parseService as Parse;
use \App\Core\addressService as Address;
class K_parseProbateAddress implements _Contract{

	public function __construct($text){
		$this->text = $text;
	}
	

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		$lines 	= Parse::removeShortLines($this->text,10);
		$line   = Parse::getProbateLine($lines);

		$a = Parse::findNumber($string,true);
		$b = Parse::findStreetEndingIndex($string) + 1;

		if($a === false || $b === false){
			return false;
		}else{
			return Parse::sliceLine($line,$a,$b);
		}
	}



	/*public function parseLevel1(){
		#echo "<br>Probate Address1--->".$this->text;
		//$pos = strpos($this->text,"Relation")+strlen("Relation");
		$pos1 = $this->strposProbate($this->text,"Relation")+strlen("Relation");
		//echo __LINE__ . ": " . $pos1 . " ----  " . $pos2;
		$pos2 = strpos($this->text, "Address:", $pos1);
		$string = substr($this->text,$pos1, $pos2-$pos1);
		//echo "<br>Probate Address1--->".$string;
		
		$pos1 = $this->detectNumber($string);
		$string = substr($string,$pos1);
		//echo "<br>Probate Address2--->".$string;
		
		$AD  = $this->AD = new addressParser($string, $this->zip);
		$state = $AD->stateFinderLevel1();
		
		$zip 	= $AD->zipFinderLevel1();
		if($zip == -1){
			$zip   = $AD->zipFinderLevel2($state, $this->zip);
		}
		
		if($zip == -1){
			$city = -1;
			$street = -1;
		}else{
			$pos2 = strpos($string,$zip) + strlen($zip);
			$string = substr($string,0, $pos2);
			//echo "<br>Probate Address3--->".$string;
		
			$AD = new addressParser($string, $this->zip);
			$city  = $AD->cityFinderLevel2($zip);
			$street = $AD->streetFinderLevel2($city);
		}
		
		if($street == -1){
			$street = $AD->streetFinderLevel3($state);
		}
		
		$this->result['street'] = $street;
		$this->result['city'] 	= $city;
		$this->result['state'] = $state;
		$this->result['zip'] 	= $zip;
	}*/
	

	public function parseLevel2(){
		/*$AD = $this->AD;
		#echo "<H5 style='color:purple'>Probate Address Parse Level 2</H5>";
		if($this->result['zip']  == -1){
			$this->result['zip']   = $AD->zipFinderLevel2($this->result['state'], $this->zip);
		}
			
		$city  = $AD->cityFinderLevel3($this->result['state'], $this->zip);
		$this->result['city'] = $city;
		$this->result['street'] = $AD->streetFinderLevel2($city);
		
		if($this->result['street'] == -1){
			$this->result['street'] = $AD->streetFinderLevel3($this->result['state']);
		}*/
	}
	


    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function  testLevel1(){
		foreach($this->result as $key => $value){
			if($value == -1 and $key != 'zip'){
				return -1;
			}
		}
	}
	

	/*public function testLevel2(){
		if($this->result['city'] == ""){return -1;}
		if($this->result['street'] == ""){return -1;}
	}*/

}