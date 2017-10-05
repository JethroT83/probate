<?php

namespace App\Core;

use \App\Core\parseService as service;
class E_parseDeceasedName implements _Contract{

	public function __construct($text){
		$this->text = $text;
	}
	
    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){
		$pos1 = strpos($this->text,"Name:")+strlen("Name:");
		$pos2 = strpos($this->text,"Address",$pos1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
		return trim($string);
	}


	public function parseLevel2(){
		/*$p = new parseDeceasedName($this->result);
		$spaces = $p->countSpaces();
		if(*/
	}



    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
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
	

}
?>