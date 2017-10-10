<?php

namespace App\Core;

use \App\Core\Services\ParseService as Parse;

class C_parseProbateDate  implements _Contract{

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################


	public function parseLevel1(){

		$lines  =  Parse::removeShortLines($this->text, 10);
		foreach($lines as $i => $line){
			$line = preg_replace("/\s+/","",$line);
			if(stripos($line,"DateofBirth:") !== false){
				$e = explode(":",$line);
				return substr($e[2],0,10);
			}
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