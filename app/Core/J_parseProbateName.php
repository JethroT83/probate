<?php
namespace App\Core;
	
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;

Class J_parseProbateName implements _Contract{
	
    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		$lines = Parse::removeShortLines($this->text,10);
		$lines = Parse::indexArray($lines);
		
		$line  = Parse::getProbateLine($lines);
		if($line === false){return false;}
		// Removes all non-proper cased words
		$line 	= Parse::removeCase($line);
		$index 	= Parse::findNumber($line, true);

		if($index === false){
			return false;
		}else{
			return Parse::sliceLine($line,0,$index);
		}

	}


	public function parseLevel2(){

	}


    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function testLevel1($result){
		return true;
	}
}

?>