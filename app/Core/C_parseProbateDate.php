<?php

namespace App\Core;
use \app\parseService as service;
class C_parseProbateDate  implements _Contract{

	public function __construct($text){
		$this->text = $text;
	}
	
    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){
		$pos1 = strpos($this->text,"File Date:")+strlen("File Date:");
		$pos2 = strpos($this->text,"Date of Death:",$pos1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
			return trim($string);
	}

	public function parseLevel2(){
		$D = new parseDate($this->result);
		$result = $D->parseNoSpace();
		return $D->findEnd($result);
		
	}
	
    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function testLevel1(){
		$D = new parseDate($this->result);
		$testSpace =  $D->testSpace();
	}
	
}