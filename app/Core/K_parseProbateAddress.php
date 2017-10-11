<?php
namespace App\Core; 
use Illuminate\Support\Facades\Cache as Cache;
use \App\Core\Services\ParseService as Parse;
use \App\Core\Services\AddressService as Address;
class K_parseProbateAddress implements _Contract{


    #########################################################
    ################    PARSING FUNCTIONS    ################
    #########################################################
	public function parseLevel1(){

		#$address = Cache::get('proAddress');
		#return $address['street'];

#$page = Cache::get('page');
#$pp = 21;
		//Remove bad lines
		$lines 	= Parse::removeShortLines($this->text,10);
		
		//Re-index array
		$lines  = Parse::indexArray($lines);
		
#if($page == 5){var_dump($lines);}

		//Find line with the probate information
		$line   = Parse::getProbateLine($lines);
		if($line === false){return false;}
#if($page == 5){echo $line;}
		//Find where the address begins
		$a = Parse::findNumber($line,true);

		//Shorten the line to where the address number starts
		$shortLine = Parse::sliceLine($line,$a);

		//Count Commas
		$c = substr_count($shortLine,",");
#if($page == $pp){		
	#echo "\npage->".$page."-shortLine->".$shortLine."-commas->".$c;
#}
		switch($c){

			//If there is one comma, it is 'city, state'
			case 1:


				$e = explode(",",$shortLine);// break address city, state
				$addressCity = trim($e[0]);
#if($page == $pp){
	#echo "\npage->".$page."-addressCity->".$addressCity;
#}
				$e = explode(" ",$addressCity);// break address city into words
				$e = array_slice($e,0,-1);// City is at least two words, one can be removed
#if($page == $pp){
	#echo "\npage->".$page."-e->".json_encode($e,JSON_PRETTY_PRINT);
#}				
				//Going from right to left, search for a street ending
				$w = count($e)-1;
				$c = 0;
				for($i=$w;$i>0;$i--){

					$word = $e[$i];
					if(Address::isStreetEnding($word)){
						//When a street ending is found, get the begining of the short line to the street ending
						$address = Parse::sliceLine($addressCity,0,$i+1);

						return Address::googleVerify($address,"street",1);

					}
				}
				break;


			//If there are two commas, it is 'address, apt city, state'
			case 2:
				$e = explode(",",$shortLine);// break address apt city, state
				$address = trim($e[0]);
				$aptCity = trim($e[1]);

				$e = explode(" ",$aptCity);// break address city into words

				$address = $address." ".trim($e[0]);

				return Address::googleVerify($address,"street",1);

				break;
		}

		return false;
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
