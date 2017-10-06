<?php
namespace App\Core;

	
use \App\Core\ParseService as service;
class D_parseDeathDate implements _Contract{
	
	public function __construct($text){
		$this->text = $text;
	}
	

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		return Parse::parseKeyWord($this->text,'DateofDeath:', 9, array(5,11));
	}

	public function parseLevel2(){

	}
	

    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function testLevel1(){
		if(strlen($this->result) == 10){
			return 1;
		}else{
			return -1;
		}
	}
	


}
