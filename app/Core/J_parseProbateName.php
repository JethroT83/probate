<?php
namespace App\Core;
	
use \App\Core\ParseService as Parse;
use \App\Core\ParseName as Name;
Class J_parseProbateName implements _Contract{
	
	#public function __construct($text){
	#	$this->text = $text;
	#}



    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		$lines = Parse::removeShortLines($this->text,10);
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


	public function parseLeve2(){
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
	public function testLevel1(){
		return;
	}
}

?>