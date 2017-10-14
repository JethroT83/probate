<?php
namespace App\Core;
use Illuminate\Support\Facades\Cache as Cache;
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;
class F_parseDeceasedAddress implements _Contract{
	

    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		$lines  =  Parse::removeShortLines($this->text, 10);

		foreach($lines as $i => $line){
			$pos = stripos($line,'address:');
			if(stripos($line,'address:') !== false){
				$string = trim(substr($line,8));
				break;
			}

			if($i == 8){break;}
		}

		// String returns -- Address, City, State Zip
		if(!isset($string) || strlen($string) == 0){return false;}
		$e 		= explode(",",$string);

		$address =false;
		switch($e){

			case (count($e) == 3):
				$address = trim($e[0]);
				break;

			case (count($e) == 4):
				$address = trim($e[0]) ." ". trim($e[1]);
				break;
		}


		return Address::googleVerify($address,'street',0);
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
