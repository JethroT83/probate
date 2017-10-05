<?php
namespace App\Core;
	
use \App\Core\parseService as service;
use \App\Core\parseName as parseName;
Class J_parseProbateName implements _Contract{
	
	public function __construct($text){
		$this->text = $text;
	}
	
    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){
		$lines = explode("\n",$this->text);

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

		return trim(substr($line,0,$p));

	}


	public function parseLeve2(){
		return;
	}


    #########################################################
    ################    TESTING FUNCTIONS    ################
    #########################################################
	public function testLevel1(){
		return;
	}
}

?>