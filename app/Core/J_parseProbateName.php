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

		$e = explode(" ",$line);

		$name = "";
		foreach($e as $i => $word){
		    
		    if(Parse::checkNumber($word)){break;}

		    switch($word){

		    	case (substr(strtolower($word),0,3) == "exe"):
		    		break;

		    	case (		substr(strtolower($word),0,2) == "af"
		    			&&	substr(strtolower($word),-3) == "ant"):
		    		break;

		        case (Parse::checkProper($word) === true):
		            $name.= $word . " ";
		            break;
		        
		        case (Parse::checkInitial($word) === true):
		            $name.=$word ." ";
		            break;
		    }
		}



		return trim($name);

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