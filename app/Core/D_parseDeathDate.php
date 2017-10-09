<?php
namespace App\Core;

	
use \App\Core\Services\ParseService as Parse;

class D_parseDeathDate implements _Contract{
	

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		return Parse::parseKeyWord($this->text,'Date of Death:', 10, array(5,11));
	}

	public function parseLevel2(){

	}
	

    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function testLevel1($result){
		if(strlen($result) == 10){
			return true;
		}else{
			return false;
		}
	}
	


}
