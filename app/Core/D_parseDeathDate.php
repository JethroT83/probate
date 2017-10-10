<?php
namespace App\Core;

	
use \App\Core\Services\ParseService as Parse;

class D_parseDeathDate implements _Contract{
	

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		$lines  =  Parse::removeShortLines($this->text, 10);
		foreach($lines as $i => $line){
			$line = preg_replace("/\s+/","",$line);
			if(stripos($line,"DateofBirth:") !== false){
				$e = explode(":",$line);
				return substr($e[3],0,10);
			}
		}
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
