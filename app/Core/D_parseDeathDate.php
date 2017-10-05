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
		$pos1 = strpos($this->text,"Death:")+strlen("Death:");
		//$pos2 = strpos($this->text,"Date", $pos1);
		//$pos2 = $this->nextWord($this->text,$pos1+2);
		$string = substr($this->text,$pos1,11);
		$string = trim($string);
		//echo "This is string-->". $string;
		$D = new parseDate($string);
		$r = $D->parseNoSpace();
		//echo "<br>This is parse no space--->" . $r;
		return $r;
	}
	

	public function parseLevel2(){
		$D = new parseDate($this->result);
		return $D->findEnd($this->result);
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
