<?php

namespace App\Core;

use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\DateService as Date;
class C_parseProbateDate  implements _Contract{

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


	public function parseLevel1(){

		$lines  =  Parse::removeShortLines($this->text, 10);
		$line   =  Date::getProbateDateLine($lines);

		$e = explode(":",$line);
		if(!isset($e[2])){
			return false;
		}else{
			return substr($e[2],0,10);
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