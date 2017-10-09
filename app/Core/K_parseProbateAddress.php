<?php
namespace App\Core; 
	
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;
class K_parseProbateAddress implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		$lines 	= Parse::removeShortLines($this->text,10);
		$lines  = Parse::indexArray($lines);
		$line   = Parse::getProbateLine($lines);

		$a = Parse::findNumber($line,true);
		$b = Address::getStreetEndingIndex($line) + 1;

		if($a === false || $b === false){
			return false;
		}else{
			return Parse::sliceLine($line,$a,$b);
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