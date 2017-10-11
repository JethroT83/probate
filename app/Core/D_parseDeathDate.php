<?php
namespace App\Core;

	
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\DateService as Date;
class D_parseDeathDate implements _Contract{
	

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		$lines  =  Parse::removeShortLines($this->text, 10);
		$line   =  Date::getProbateDateLine($lines);

		$e = explode(":",$line);
		return substr($e[3],0,10);
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
