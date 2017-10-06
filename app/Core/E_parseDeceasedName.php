<?php

namespace App\Core;

use \App\Core\parseService as Parse;
class E_parseDeceasedName implements _Contract{

	
    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){
		return Parse::parseKeyWord($this->text,'Name:', null, array(2,5));
	}


	public function parseLevel2(){
		/*$p = new parseDeceasedName($this->result);
		$spaces = $p->countSpaces();
		if(*/
	}



    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function testLevel1(){
		/*$p = new parseDeceasedName($this->result);
		$spaces = $p->countSpaces();
		if($space == 1){
			return 1;
		else{
			return -1;
		}*/
		return 1;
	}
	

}
?>