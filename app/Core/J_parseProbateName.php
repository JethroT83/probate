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
		/*$lines = explode("\n",$this->text);

		$l = "";
		$search = false;
		foreach($lines as $i => $line){

			if(strpos($line,"Relation") !== false){
				$search = true;
				continue;
			}

			if($search === true){
				$name = parseName::findName($line);
				if($name !== false){
					$l = $line;
					break;
				}
			}
		}

		$p = strpos($l, "am");

		return trim(substr($line,0,$p));*/
	}


    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function testLevel1($result){
		return true;
	}
}

?>