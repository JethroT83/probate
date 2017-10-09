<?php

namespace App\Core;

use \App\Core\Services\ParseService as Parse;

class C_parseProbateDate  implements _Contract{

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################

	public function parseLevel1(){
		return Parse::parseKeyWord($this->text,'File Date:', 10, array(5,11));
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