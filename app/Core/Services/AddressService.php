<?PHP
namespace App\Core\Services;
use \App\Core\Services\ParseService as Parse;
use Illuminate\Support\Facades\Cache as Cache;
class AddressService{


	###################################	STREET ENDINGS	#########################################
	public static $streetEndings = array('alley',	'annex',	'arcade',	'avenue',	'bayoo',	'beach',	'bend',	'bluff',	'bluffs',	'bottom',	'boulevard',	'branch',	'bridge',	'brook',	'brooks',	'burg',	'burgs',	'bypass',	'camp',	'canyon',	'cape',	'causeway',	'center',	'centers', 'cir',	'circle',	'circles',	'cliff',	'cliffs',	'club',	'common',	'corner',	'corners',	'course',	'court',	'courts',	'cove',	'coves',	'creek',	'crescent',	'crest',	'crossing',	'crossroad',	'curve',	'dale',	'dam',	'divide',	'drive',	'drives',	'estate',	'estates',	'expressway',	'extension',	'extensions',	'fall',	'falls',	'ferry',	'field',	'fields',	'flat',	'flats',	'ford',	'fords',	'forest',	'forge',	'forges',	'fork',
		'forks',	'fort',	'freeway',	'garden',	'gardens',	'gateway',	'glen',	'glens',	'green',	'greens',	'grove',	'groves',	'harbor',	'harbors',	'haven',	'heights',	'highway',	'hill',	'hills',	'hollow',	'inlet',	'interstate',	'island',	'islands',	'isle',	'junction',	'junctions',	'key',	'keys',	'knoll',	'knolls',	'lake',	'lakes',	'land',	'landing',	'lane',	'light',	'lights',	'loaf',	'lock',	'locks',	'lodge',	'loop',	'mall',	'manor',	'manors',	'meadow',	'meadows',	'mews',	'mill',	'mills',	'mission',	'moorhead',	'motorway',	'mount',	'mountain',	'mountains','neck',	'orchard',	'oval',	'overpass',	'park',	'parks',	'parkway',	'parkways',	'pass',
		'passage',	'path','pike',	'pine',	'pines',	'place',	'plain',	'plains',	'plaza',	'point',	'points',	'port',	'ports',	'prairie',	'radial',	'ramp',	'ranch',	'rapid',	'rapids',	'rest',	'ridge',	'ridges',	'river',	'road',	'roads',	'route',	'row',	'rue',	'run',	'shoal',	'shoals',	'shore',	'shores',	'skyway',	'spring',	'springs',	'spur',	'spurs',	'square',	'squares',	'station',	'stream',	'street',	'streets',	'summit',	'terrace',	'throughway',	'trace',	'track',	'trail',	'tunnel',	'turnpike',	'underpass',	'union',	'unions',	'valley',	'valleys',	'viaduct',	'view',	'views',	'village',	'villages',	'ville',	'vista',	'walk',	'walks',	'wall',	'way',	'ways',	'well',	'wells',
		'aly',	'anx',	'arc',	'ave',	'byu',	'bch',	'bnd',	'blf',	'blfs',	'btm',	'blvd',	'br',	'brg',	'brk',	'brks',	'bg',	'bgs',	'byp',	'cp',	'cyn',	'cpe',	'cswy',	'ctr',	'ctrs',	'cir',	'cirs',	'clf',	'clfs',	'clb',	'cmn',	'cor',	'cors',	'crse',	'ct',	'cts',	'cv',	'cvs',	'crk',	'cres',	'crst',	'xing',	'xrd',	'curv',	'dl',	'dm',	'dv',	'dr',	'drs',	'est',	'ests',	'expy',	'ext',	'exts',	'fall',	'fls',	'fry',	'fld',	'flds',	'flt',	'flts',	'frd',	'frds',	'frst',	'frg',	'frgs',	'frk',	'frks',	'ft',	'fwy',	'gdn',	'gdns',	'gtwy',	'gln',	'glns',	'grn',	'grns',	'grv',	'grvs',	'hbr',	'hbrs',	'hvn',	'hts',	'hwy',	'hl',	'hls',	'holw',	'inlt',	'i',	'is',	'iss',	'isle',	'jct',	'jcts',	'ky',	'kys',	'knl',	'knls',
		'lk',	'lks',	'land',	'lndg',	'ln',	'lgt',	'lgts',	'lf',	'lck',	'lcks',	'ldg',	'loop',	'mall',	'mnr',	'mnrs',	'mdw',	'mdws',	'mews',	'ml',	'mls',	'msn',	'mhd',	'mtwy',	'mt',	'mtn',	'mtns',	'nck',	'orch',	'oval',	'opas',	'park',	'park',	'pkwy',	'pkwy',	'pass',	'psge',	'path',	'pike',	'pne',	'pnes',	'pl',	'pln',	'plns',	'plz',	'pt',	'pts',	'prt',	'prts',	'pr',	'radl',	'ramp',	'rnch',	'rpd',	'rpds',	'rst',	'rdg',	'rdgs',	'riv',	'rd',	'rds',	'rte',	'row',	'rue',	'run',	'shl',	'shls',	'shr',	'shrs',	'skwy',	'spg',	'spgs',	'spur',	'spur',	'sq',	'sqs',	'sta',	'strm',	'st',	'sts',	'smt',	'ter',	'trwy',	'trce',	'trak',	'trl',	'tunl',	'tpke',	'upas',	'un',	'uns',	'vly',
		'vlys',	'via',	'vw',	'vws',	'vlg',	'vlgs',	'vl',	'vis',	'walk',	'walk',	'wall',	'way',	'ways',	'wl',	'wls');

	
	###################################	TOWN ENDINGS	#########################################
	public static $townEndings = array("township","borough","city");


	###################################		STATES 		#########################################
	public static $states = array('AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA',
						'MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN',
						'TX','UT','VT','VA','WA','WV','WI','WY','AS','DC','FM','GU','MH','MP','PW','PR','VI','AE','AA','AE','AE',
						'AE','AP');

	# State
	public static  function getStateIndex($line,$i=0){
		
		$index = Parse::findCaseIndex($line,'UPPER');
		$a = $index + $i;

		if($index === false || $i > 25){
			return false;
		}else{
		    $pIndex = $index + $i;
			$state = Parse::sliceLine($line,$index,$index);

			if(strlen($state) == 2 && ctype_alpha($state) && strtoupper($state) == $state){
				
				return $pIndex;
			}else{
			    $line = Parse::sliceLine($line,$index+1);
				return self::getStateIndex($line,$pIndex+1);
			}
		}

	}

	#Address
	public static function getStreetEndingIndex($line){

		// Street ending index must be 2 word from the number
		$a = Parse::findNumber($line,true);
		$e = explode(' ',$line);

		foreach($e as $index => $word){
			if($index > $a){
				if(in_array(strtolower($word),self::$streetEndings)){
					return $index;
				}
			}
		}

		return false;
	}


	public static function isStreetEnding($string){

		if(substr($string,-1) == "."){
			$string = substr($string,0,-1);
		}

		if(in_array(strtolower($string),self::$streetEndings)){	
			return true;
		}else{
			return false;
		}
	}


	public static function findState($string){

		$string = str_replace("-"," ",$string);
		$e = explode(" ",$string);
	
		foreach($e as $index => $word){
			if(in_array($word,self::$states)){
				return $index;
			}
		}

		return false;
	}


	public static function findZip($string){

		$e = explode(" ",$string);

		foreach($e as $index => $word){
			if(preg_match('/(^\d{5}(?:[\s]?[-\s][\s]?\d{4})?$)/',$word)){
				return $index;
			}
		}

		return false;
	}


	# ########################################################################
	#	Sends cURL request to Google's API
	#	The address string should be 'Street City, State Zip'
	# ########################################################################
	public static function googleAddressLookup($addressString){

		$key = "AIzaSyBtaQ93f1eXlvE8JiUkHF7hsjiJzejUrMQ";
		$addressString = urlencode($addressString);
		
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=";
		$url.= $addressString;
		$url.= "&key=".$key;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$geoloc = json_decode(curl_exec($ch), true);

		if (count($geoloc['results']) > 0 ) {
			$a = $geoloc['results'][0]['formatted_address'];

			$e = explode(",",$a);
			$stateZip = trim($e[2]);
			$ee = explode(" ",$stateZip);
			$result['street'] = trim($e[0]);
			$result['city']   = trim($e[1]);
			$result['state']  = $ee[0];
			$result['zip']    = $ee[1];
			$result['country']= trim($e[3]);

			return $result;
		}else{
			return false;
		}
	}


	public static function getDeceasedAddressString($text){

		$lines  =  Parse::removeShortLines($text, 10);

		$addressString = false;
		foreach($lines as $i => $line){
			$pos = stripos($line,'address:');
			if(stripos($line,'address:') !== false){
				$addressString = trim(substr($line,8));
				break;
			}

			if($i == 8){break;}
		}

		if($addressString === false){
			return false;
		}else{
			return $addressString;
		}
	}


	public static function getDeceasedAddress($text){

		$addressString = self::getDeceasedAddressString($text);

		if($addressString === false){
			return false;
		}else{
			return self::googleAddressLookup($addressString);
		}
	}


	# ########################################################################
	#	Function Why is supposed to satify the unit tests, despite the OCR doing
	#	a terrible job.  Seriously, how is a state and zip code missing
	# ########################################################################
	private static function why($line){
		$e = explode(",",$line);
		$e = explode(" ",trim($e[0]));
		return count($e)-1;	
	} 


	# ########################################################################
	# Occasionally, the state and zip code is on following line
	# ########################################################################
	public static function retrieveStateZipLine($text){

		//Worse case there is a zip code of 5 characters,
		//so lines must be 5 characters of more
		$lines 	= Parse::removeShortLines($text,5);

		//Re-index array
        $lines  = Parse::indexArray($lines);

		//Finds the probate line
		$index = Parse::findProbateLine($lines);

		if($index !== false){
			
			//If probate line is found, a lost state
			// and zip might be on the next line
			$index++;

			$line = $lines[$index];

			$a = self::findState($line);//Find State
			$b = self::findZip($line);//Find Zip

			$result = array();
			if($a !== false){
				$result['state'] = Parse::sliceLine($line,$a,$a);
			} 

			if($b !== false){
				$result['zip'] = Parse::sliceLine($line,$b,$b);
			} 

			return $result;
		}

		return false;

	}



	public static function getProbateAddressString($text){

		//Remove bad lines
		$lines 	= Parse::removeShortLines($text,10);
		
		//Re-index array
		$lines  = Parse::indexArray($lines);

		//Find line with the probate information
		$line   = Parse::getProbateLine($lines);
	
		if($line === false){return false;}

		//Find where the address begins
		$a = Parse::findNumber($line,true);

		$funcs = array("self::findZip","self::findState","self::why");
		foreach($funcs as $i => $func){
			$index = call_user_func($func,$line);
			
			if($index !== false){break;}
		}

		if($index === false){return false;}
		$b = $index;

		//Shorten the line to where the address number starts
		return Parse::sliceLine($line,$a,$b);
	}


	public static function getProbateAddress($text){

		//Shorten the line to where the address number starts
		$addressString = self::getProbateAddressString($text);

		//Get the Google information
		return self::googleAddressLookup($addressString);
	}


	# ########################################################################
	#	Google Geolocate is only as good as the information inputted
	#   Many times the OCR screws up real bad and Google simply does 
	#   have enough information to rememdy it.  This function is a
	# 	test, which will determine if the information from Google is good.
	# ########################################################################
	public static function testGoogleAddress($text,$type){

		if($type == 0){	$googleData = Cache::get('decAddress');  //Deceased Address
						$addressString = self::getDeceasedAddressString($text);

		}else{			$googleData = Cache::get('proAddress');
						$addressString = self::getProbateAddressString($text);}//Probate Address

		// Check street
		$pe = explode(" ",trim($addressString));
		$ge = explode(" ",trim($googleData['street']));

#$page = Cache::get('page');
#if($page == 12){
#	echo "\n".__LINE__."--ge-->".json_encode($ge,JSON_PRETTY_PRINT)."--pe-->".json_encode($pe,JSON_PRETTY_PRINT);
#}
		//Verify Same address number
		if(		$pe[0] != $ge[0] //A valid google address always starts the same
			|| 	(!isset($ge[1]) || !isset($pe[1])) // There needs to be something to compare
			||  ( Parse::findNumber($ge[0],true)===false) // Address needs to start with number
			|| 	( Parse::compareStrings($pe[1],$ge[1])=== false) // Street name can be only slightly different
			){return false;} 

		//Check city
		$test 	= false;
		$e 		= explode(" ",$googleData['city']);
		$city 	= $e[0];
		foreach($pe as $i => $word){
			if(Parse::compareStrings($word,$city) && $test===false){
				$test=true;
			}
		}

		//Verified that Google's address is accurate
		if($test === true){ return true;}

		return false;
	}


	public static function stripCity($parsed,$type){

		if($type == 0){	$googleData = Cache::get('decAddress');  //Deceased Address
		}else{			$googleData = Cache::get('proAddress');}//Probate Address

		$googleCity = $googleData['city'];

		$e 		= explode(" ",$parsed);
		$pWord 	= end($e);

#$page = Cache::get('page');
#echo "\npage-->".$page;
#if($page == 12){
#	echo "\n\n".__LINE__."--googleCity-->".$googleCity."--parsed-->".$parsed;
#}


		$ge  	= explode(" ",$googleCity);
		foreach($ge as $i => $gWord){
			if(strtolower($gWord) == strtolower($pWord)){
				$parsed = str_replace($pWord,"",$parsed);
				break;
			}
		}

		return trim($parsed);
	}



	# ########################################################################
	#	Checks the parsed out address against address from Google APIs
	#	After looking at data, some guidelines were decerned
	#		- if Google and the address start with the same number, Google tends to be correct 
	#       - if the strings slightly off, the OCR did not read the text correctly
	#
	# 	$parsed - the parsed out address
	# 	$locale  - street - city - state - zip - coutnry
	#	$type - 0 deceased 1 probate  
	# ########################################################################
	public static function googleVerify($parsed,$locale,$type){

		#if(strlen($parsed) == 0){return false;}
		if($type == 0){	$googleData = Cache::get('decAddress');  //Deceased Address
		}else{			$googleData = Cache::get('proAddress');}//Probate Address
		// The locale parameters correspond to the array in function googleAddressLookup
		if(isset($googleData[$locale])){
			$googleAddress = $googleData[$locale];
		}else{
			$googleAddress = false;
		}

		// If Google doesn't return a result, then the parsed value is returned
		if(strlen($googleAddress) == 0){return $parsed;}

		switch($locale){

			case "street":

				//Sometimes a street ending is also a town name
				// ei. 208 Ocean Road Spring Lake parses as 208 Ocean Road Spring
				// This function takes the city out of the street name
				$parsed = self::stripCity($parsed,$type);

				$pe = explode(" ",$parsed);
				$ge = explode(" ",$googleAddress);

				// Use Google if the streets names are off
				// or count of the words in the street name are different
				if($pe[1] != $ge[1] //|| //any pe word in google city name
				 /*count($pe) != count($ge)*/ ){
					return $googleAddress;
				}else{
					return $parsed;
				}
				break;

			case "city":

				$pe = explode(" ",$parsed);
				$ge = explode(" ",$googleAddress);

				//Towns may have two words...  sometimes... and comparing the first word is fine
				if(		$pe[0] != $ge[0]
					//Parsed sometimes comes back with "Township" or "Borough"
					||  in_array(strtolower($pe[0]),self::$streetEndings) ){

					return $googleAddress;
				}else{
					return $parsed;
				}

			case "state":
				return $googleAddress;

			case "zip":
				 return $googleAddress;

		}
	}

}

?>