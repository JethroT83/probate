<?php

namespace App\Core;
use \app\parseService as Parse;
class C_parseProbateDate  implements _Contract{

	public function __construct($text){
		$this->text = $text;
	}
	
    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################

	public function parseLevel1(){
		return Parse::parseKeyWord($this->text,'FileDate:', 9, array(5,11));
	}

	public function parseLevel2(){

	}
	
    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function testLevel1(){

	}
	
}