<?php
Class parseDocket extends textParser{

	public function __construct($text){
		$this->text = $text;
		$this->onController();
		return $this->result;
	}
	
	function parseLevel1(){
		$pos1 = strpos($this->text,"Docket Number")+15;
		//$pos2 = strpos($this->text,"Name:",$pos1);
		$string = substr($this->text,$pos1,10);
		$d = "";
		for($x=0;$x<=strlen($string);$x++){
			$l = substr($string, $x,1);
			if(is_numeric($l)){
				$d .= $l;
			}
		}
		
		return trim($d);
	}
	
	function testLevel1(){
		$array = array(0,1,3,4,5,6,7,8,9,'0','1','2','3','4','5','6','7','8','9');
		$length = strlen($this->result);
		for($x=0;$x<=$length;$x++){
			$l = substr($this->result,$x,1);
			//if(array_search($l,$array)==false){
			if(!is_numeric($l)){
				return -1;
			}
		}
		return 1;
	}
	
	function parseLevel2(){
		$string = "";
		for($x=0;$x<=strlen($this->result);$x++){
			if(is_numeric(substr($this->result,$x))){
				$string .= substr($this->result,$x,1);
				if(strlen($string) == 7){break;}
			}
		}
		return $string;
	}
	
	public function onController(){
		$this->result = $this->parseLevel1();
		$test = $this->testLevel1();
		if($test == -1){
			$this->result = $this->parseLevel2();
		}
	}
}

Class parseCaseType extends textParser{

	public function __construct($text){
		$this->caseTypes = array("PROBATE","NEXT OF KIN AFFIDAVIT", "SPOUSE AFFIDAVIT");
		$this->text = $text;
		$this->onController();
		return $this->result;
	}
	
	
	public function parseLevel1(){
		$pos1 = strpos($this->text,"Case Type:")+strlen("Case Type:");
		$pos2 = strpos($this->text,"DocketNumber:");
		//$pos2 = $this->nextWord($this->text,$pos1+1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
		$string = strtoupper($string);
		
		foreach($this->caseTypes as $i => $type){
			$type =  strtoupper($type);
			if(stripos($string, $type) !== false){
				return $type;
			}
		}
		return trim($string);
	}
	
	public function testLevel1(){
		if(array_search($this->result,$this->caseTypes)!==false){
			return 1;
		}else{
			return -1;
		}
	}
	
	public function parseLevel2(){
		$f = fopen("caseTypes_NotFound.txt","w");
		fwrite($f,$this->result);
		fclose($f);
	}

	public function onController(){
		$this->result = $this->parseLevel1();
		$test = $this->testLevel1();
		if($test == -1){
			$this->result = $this->parseLevel2();
		}
	}

}


class dateParser extends textParser{
	public function __construct($result){
		$this->result = $result;
	}
	
	#This eliminates spaces in string
	public function testSpace(){
		$string = "";
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result, $x);
			
			if(ord($l) ==32){
				return -1;
			}
		}
		return 1;
	
	}
	
	#This is eliminate all the spaces
	public function parseNoSpace(){
		$string = "";
		for($x=0;$x<=10;$x++){
			$l = substr($this->result, $x,1);
			
			if(ord($l) !=32){
				$string .= $l;
			}
		}
		return $string;
	}
	
	public function findEnd($result){
		for($x=strlen($result);$x>0;$x--){
			$l = substr($result, $x);
			if(ord($l) ==47){
				return substr($result, 0,$x+4);
			}
		}
	}

}



class parseProbateDate extends textParser{

	public function __construct($text){
		$this->text = $text;
		$this->onController();
		return $this->result;
	}
	
	function parseLevel1(){
		$pos1 = strpos($this->text,"File Date:")+strlen("File Date:");
		$pos2 = strpos($this->text,"Date of Death:",$pos1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
			return trim($string);
	}
	
	
	public function testLevel1(){
		$D = new dateParser($this->result);
		$testSpace =  $D->testSpace();
	}
	
	public function parseLevel2(){
		$D = new dateParser($this->result);
		$result = $D->parseNoSpace();
		return $D->findEnd($result);
		
	}
	
	public function onController(){
		$this->result = $this->parseLevel1();
		$test = $this->testLevel1();
		if($test == -1){
			$this->result = $this->parseLevel2();
		}
	}

}


class parseDeathDate extends textParser{
	public function __construct($text){
		$this->text = $text;
		$this->onController();
	}
	
	public function parseLevel1(){
		$pos1 = strpos($this->text,"Death:")+strlen("Death:");
		//$pos2 = strpos($this->text,"Date", $pos1);
		//$pos2 = $this->nextWord($this->text,$pos1+2);
		$string = substr($this->text,$pos1,11);
		$string = trim($string);
		//echo "This is string-->". $string;
		$D = new dateParser($string);
		$r = $D->parseNoSpace();
		//echo "<br>This is parse no space--->" . $r;
		return $r;
	}
	
	public function testLevel1(){
		if(strlen($this->result) == 10){
			return 1;
		}else{
			return -1;
		}
	}
	
	public function parseLevel2(){
		$D = new dateParser($this->result);
		return $D->findEnd($this->result);
	}


	public function onController(){
		$this->result = $this->parseLevel1();
		$test = $this->testLevel1();
		if($test == -1){
			$this->result = $this->parseLevel2();
		}
	}
}

class nameParser extends textParser{
	public function __construct($result){
		$this->result = trim($result);
	}
	
	public function countSpaces(){
		$n = 0;
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result, $x);
			if(ord($l) ==32){
				$n++;
			}
		}
		return $n;
	}
	
	
	public function detectMiddleInitial(){
		$n = 0;
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result, $x,1);
			if(ord($l) ==46){
				$a = substr($this->result, $x-2,1);
				$b = substr($this->result, $x-1,1);
				$c = $l;
				$d = substr($this->result, $x+1,1);
				
				if(ord($a) == 32 && ord($d)==32){
					return 1;
				}
			}
		}
		return -1;
	}
	
	public function removeMiddleInitial(){
		$n = 0;
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result, $x,1);
			if(ord($l) ==46){
				$a = substr($this->result, $x-2,1);
				$b = substr($this->result, $x-1,1);
				$c = $l;
				$d = substr($this->result, $x+1,1);
				
				if(ord($a) == 32 && ord($d)==32){
					$initialText  = $a.$b.$c;
					return str_replace($initialText, "", $this->result);
				}
			}
		}
		return -1;
	}

}

class parseDeceasedName extends textParser{

	public function __construct($text){
		$this->text = $text;
		$this->onController();
		return $this->result;
	}
	
	public function parseLevel1(){
		$pos1 = strpos($this->text,"Name:")+strlen("Name:");
		$pos2 = strpos($this->text,"Address",$pos1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
		return trim($string);
	}
	
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
	
	public function parseLevel2(){
		/*$p = new parseDeceasedName($this->result);
		$spaces = $p->countSpaces();
		if(*/
	}


	public function onController(){
		$this->result = $this->parseLevel1();
		$test = $this->testLevel1();
		if($test == -1){
			$this->result = $this->parseLevel2();
		}
	}
}

Class parseProbateName extends textParser{
	
	public function __construct($text, $stop = "Exe"){
		$this->text = $text;
		$this->stop = $stop;
		$this->onController();
	}
	
	public function parseLevel1(){
		$pos = strpos($this->text,"Relation")+strlen("Relation");
		$pos1 = $this->strposProbate($this->text,"Relation")+strlen("Relation");
		//echo "<br>" . $pos . " ---- " , $pos1;
		$pos2 = strpos($this->text,$this->stop,$pos1);
		$string = substr($this->text,$pos1,$pos2-$pos1);
		
		for($x=0;$x<=strlen($string);$x++){
			$l = substr($string, $x, 1);
			echo ctype_upper($l);
			if(ctype_upper($l)==true){
				$start = $x;
				break;
			}
		}
		 $string = substr($string,$start);
		
		return trim($string);
	}
	
	public function testLevel1(){

	}
	
	public function parseLevel2(){

	}
	
	
	public function onController(){
		$this->result = $this->parseLevel1();
		/*$test = $this->testLevel1();
		if($test == -1){
			$this->result = $this->parseLevel2();
		}*/
	}


}

Class addressParser extends textParser{
	public function __construct($result, $zip){
		$this->zip = $zip;
		$this->result = $result;
	}
	
	public function countCommas(){
		$count = 0;
		$length = strlen($this->result);
		for($x=0;$x<$length;$x++){
			$l = substr($this->result,$x,1);
			if(ord($l) == 44){
				$count++;
			}
		}
		return $count;
	}
	
	
	//## Street
	public function streetFinderLevel1(){
		if($this->countCommas() == 2){
			$array = explode(",",$this->result);
			$city = $array[0];
		}else{
			return -1;
		}
		return $city;
	}
	
	public function streetFinderLevel2($city){
		
		//echo "String--->". $this->result;
		//echo "city---->". $city;
		
		if(stripos($this->result, $city) != false){
			$pos = strpos(strtolower($this->result), strtolower($city));
			return substr($this->result, 0, $pos);
		}else{
			return -1;
		}
	}
	
	public function streetFinderLevel3($state){
		$endings = array('alley',	'annex',	'arcade',	'avenue',	'bayoo',	'beach',	'bend',	'bluff',	'bluffs',	'bottom',	'boulevard',	'branch',	'bridge',	'brook',	'brooks',	'burg',	'burgs',	'bypass',	'camp',	'canyon',	'cape',	'causeway',	'center',	'centers',	'circle',	'circles',	'cliff',	'cliffs',	'club',	'common',	'corner',	'corners',	'course',	'court',	'courts',	'cove',	'coves',	'creek',	'crescent',	'crest',	'crossing',	'crossroad',	'curve',	'dale',	'dam',	'divide',	'drive',	'drives',	'estate',	'estates',	'expressway',	'extension',	'extensions',	'fall',	'falls',	'ferry',	'field',	'fields',	'flat',	'flats',	'ford',	'fords',	'forest',	'forge',	'forges',	'fork',
						'forks',	'fort',	'freeway',	'garden',	'gardens',	'gateway',	'glen',	'glens',	'green',	'greens',	'grove',	'groves',	'harbor',	'harbors',	'haven',	'heights',	'highway',	'hill',	'hills',	'hollow',	'inlet',	'interstate',	'island',	'islands',	'isle',	'junction',	'junctions',	'key',	'keys',	'knoll',	'knolls',	'lake',	'lakes',	'land',	'landing',	'lane',	'light',	'lights',	'loaf',	'lock',	'locks',	'lodge',	'loop',	'mall',	'manor',	'manors',	'meadow',	'meadows',	'mews',	'mill',	'mills',	'mission',	'moorhead',	'motorway',	'mount',	'mountain',	'mountains','neck',	'orchard',	'oval',	'overpass',	'park',	'parks',	'parkway',	'parkways',	'pass',
						'passage',	'path','pike',	'pine',	'pines',	'place',	'plain',	'plains',	'plaza',	'point',	'points',	'port',	'ports',	'prairie',	'radial',	'ramp',	'ranch',	'rapid',	'rapids',	'rest',	'ridge',	'ridges',	'river',	'road',	'roads',	'route',	'row',	'rue',	'run',	'shoal',	'shoals',	'shore',	'shores',	'skyway',	'spring',	'springs',	'spur',	'spurs',	'square',	'squares',	'station',	'stream',	'street',	'streets',	'summit',	'terrace',	'throughway',	'trace',	'track',	'trail',	'tunnel',	'turnpike',	'underpass',	'union',	'unions',	'valley',	'valleys',	'viaduct',	'view',	'views',	'village',	'villages',	'ville',	'vista',	'walk',	'walks',	'wall',	'way',	'ways',	'well',	'wells',
						'aly',	'anx',	'arc',	'ave',	'byu',	'bch',	'bnd',	'blf',	'blfs',	'btm',	'blvd',	'br',	'brg',	'brk',	'brks',	'bg',	'bgs',	'byp',	'cp',	'cyn',	'cpe',	'cswy',	'ctr',	'ctrs',	'cir',	'cirs',	'clf',	'clfs',	'clb',	'cmn',	'cor',	'cors',	'crse',	'ct',	'cts',	'cv',	'cvs',	'crk',	'cres',	'crst',	'xing',	'xrd',	'curv',	'dl',	'dm',	'dv',	'dr',	'drs',	'est',	'ests',	'expy',	'ext',	'exts',	'fall',	'fls',	'fry',	'fld',	'flds',	'flt',	'flts',	'frd',	'frds',	'frst',	'frg',	'frgs',	'frk',	'frks',	'ft',	'fwy',	'gdn',	'gdns',	'gtwy',	'gln',	'glns',	'grn',	'grns',	'grv',	'grvs',	'hbr',	'hbrs',	'hvn',	'hts',	'hwy',	'hl',	'hls',	'holw',	'inlt',	'i',	'is',	'iss',	'isle',	'jct',	'jcts',	'ky',	'kys',	'knl',	'knls',
						'lk',	'lks',	'land',	'lndg',	'ln',	'lgt',	'lgts',	'lf',	'lck',	'lcks',	'ldg',	'loop',	'mall',	'mnr',	'mnrs',	'mdw',	'mdws',	'mews',	'ml',	'mls',	'msn',	'mhd',	'mtwy',	'mt',	'mtn',	'mtns',	'nck',	'orch',	'oval',	'opas',	'park',	'park',	'pkwy',	'pkwy',	'pass',	'psge',	'path',	'pike',	'pne',	'pnes',	'pl',	'pln',	'plns',	'plz',	'pt',	'pts',	'prt',	'prts',	'pr',	'radl',	'ramp',	'rnch',	'rpd',	'rpds',	'rst',	'rdg',	'rdgs',	'riv',	'rd',	'rds',	'rte',	'row',	'rue',	'run',	'shl',	'shls',	'shr',	'shrs',	'skwy',	'spg',	'spgs',	'spur',	'spur',	'sq',	'sqs',	'sta',	'strm',	'st',	'sts',	'smt',	'ter',	'trwy',	'trce',	'trak',	'trl',	'tunl',	'tpke',	'upas',	'un',	'uns',	'vly',
						'vlys',	'via',	'vw',	'vws',	'vlg',	'vlgs',	'vl',	'vis',	'walk',	'walk',	'wall',	'way',	'ways',	'wl',	'wls');
		
		$pos1 = strpos($this->result, $state);
		$string = substr($this->result, 0, $pos1);
		$words = explode(" ",$string);
		
		foreach($words as $i => $word){
			$streetQ = array();
			$k = 0;
			$word = trim(strtolower($word));
			foreach($endings as $j => $ending){
				$ending = trim(strtolower($ending));
				if($word == $ending){
					//echo "This matches!!";
					//echo substr($string, 0,strpos($string,$word)+strlen($word));
					$streetQ[$k++]  = substr($string, 0,strpos($string,$word)+strlen($word));
				}
			}
		}	
		//print_R($streetQ);
		if(count($streetQ) == 1){
			return $streetQ[0];
		}else{
			return -1;
		}
		
	}
	
	//## City 
	public function cityFinderLevel1(){
		if($this->countCommas() == 2){
			$array = explode(",",$this->result);
			$city = $array[1];
		}else{
			return -1;
		}
		return $city;
	}
	
	public function cityFinderLevel2($zip){
		$endings = array("township","borough", "city");
		$result  = strtolower($this->result);
		$cityQ = array();
		
		if(isset($this->zip[$zip]['city'])){
			$city = $this->zip[$zip]['city'];
			$cityString = $this->zip[$zip]['city2'];
			$cities = explode(",",$cityString);
			$cities[count($cities)] = $city;
			
			//echo "<br><br>Curent String--->{$result}<br>";
			foreach($cities as $i => $city){
				$city = strtolower($city);
				$city = str_replace($endings, "",$city);
				//echo "<br>City: {$this->proper($city)}<br>";
				$j=0;
				if(stripos($result,$city ) != false){
					$cityQ[$j++] = $this->proper($city);
				}
			}
			
			//print_r($cityQ);
			if(count($cityQ) == 1){
				return $cityQ[0];
			}else{
				return -1;
			}
		}

		return -1;
	}
	
	public function cityFinderLevel3($state, $ZIP){
		$getCities = function($city,$string){
			if(strlen($string) > 0){
				$array = explode(",",$string);
				$array[count($array)] = $city;
			}else{
				$array[0] = $city;
			}
			return $array;
		};
		
		echo "<H5 style='color:purple'>This is ZIP check" .  count($ZIP) , "</H5>";
		
		foreach($ZIP as $z => $info){
			if(strtolower($state) == strtolower($info['state'])){
				$cities = $getCities($info['city'], $info['city2']);
				//echo "<H1 style='color:purple'>These are cities for $z" .  var_dump($cities) , "</H1>";
				foreach($cities as $c => $city){
					$city = strtolower($city);
					if(isset($ref) == 0){
						$ref[$city][0] = $z;
					}else{
						if(array_key_exists($city, $ref) != false){
							$ref[$city][count($ref[$city])] = $z;
						}else{
							$ref[$city][0] = $z;
						}
					}
				}
			}
		}
		$j=0;
		//echo "<br><br>This is the result--fdasf->" . $this->result;
		$words = explode(" ", $this->result);
		
		foreach($words as $i => $word){
			$w[$i] = strtolower(str_replace(",","",$word));
		}
		
		$words = $w;
		
		if(count($words)>4){
			unset($words[0]);
			unset($words[1]);
		}

		//echo "These are words--->";
		//print_r($words);
		$matches = array();
		$j = 0;
		foreach($ref as $city => $z){
			//echo "<br>This is close--- $city";
			$city = $this->closeMatch($city, $words);
			//echo "Close Match--->" . $city;
			if($city  != -1){
				//echo "*****  CITY: " . $city;
				$matches[$j++] = $city;
			}
		}
		//print_r($matches);
		if(count($matches == 1)){
			return $matches[0];
		}else{
			echo "no matches found.";
			return -1;
		}
	}
	
	//## States
	public function stateFinderLevel1(){
		$states = array('AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA',
						'MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN',
						'TX','UT','VT','VA','WA','WV','WI','WY','AS','DC','FM','GU','MH','MP','PW','PR','VI','AE','AA','AE','AE',
						'AE','AP');
		$stateQ = array();
		$string = "";
		for($x=0;$x<strlen($this->result);$x++){
			$l = substr($this->result,$x,1);
			if(ord($l) == 32){
				if(strlen($string) == 2){
					$stateQ[] = $string;
				}
				$string = "";
			}else{
				$string .= $l;
			}
		}
		
		$s = array();
		$j = 0;
		foreach($stateQ as $i => $state){
			if(array_search($state, $states)!==false){
				$i = array_search($state, $states);
				$s[$j++] = $states[$i];
			}
		}
		//count(count($s));
		//print_r($s);
		if(count($s) == 1){
			return $s[0];
		}else{
			return -1;
		}
	}

	
	//## ZIP
	public function zipFinderLevel1(){
		$string = "";
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result,$x,1);
			if(is_numeric($l)){
				$string .= $l;
				if(strlen($string)==5){
					break;
				}
			}else{
				$string = "";
			}
		}
		
		if(strlen($string) == 5){
			return $string;
		}else{
			return -1;
		}
	}
	
	
	public function zipFinderLevel2($state, $ZIP){
		if($state == "NJ"){
			$string = "";
			for($x=0;$x<=strlen($this->result);$x++){
				$l = substr($this->result,$x,1);
				if(is_numeric($l)){
					$string .= $l;
					if(strlen($string)==4){
						break;
					}
				}else{
					$string = "";
				}
			}
			
			$z = "0".$string;
			if(array_key_exists($z,$ZIP)!==false){
				return $z;
			}else{
				return -1;
			}
		}
	}
	
	
	
}


class parseDeceasedAddress extends textParser{
	public function __construct($text, $zip){
		$this->zip = $zip;
		$this->text = $text;
		$this->onController();
	}
	
	function parseLevel1(){
		$pos1 = strpos($this->text,"Address")+strlen("Address:");
		$pos2 = strpos($this->text,"Age:",$pos1);
		$this->r = substr($this->text,$pos1,$pos2-$pos1);
		
		//echo "Print Address--->";
		//print_r($this->r);
		
		$AD = new addressParser($this->r, $this->zip);
		
		$this->result['street'] = $AD->streetFinderLevel1();
		$this->result['city'] 	= $AD->cityFinderLevel1();
		$this->result['state'] 	= $AD->stateFinderLevel1();
		$this->result['zip'] 	= $AD->zipFinderLevel1();
		
	}
	
	public function testLevel1(){
		foreach($this->result as $i => $test){
			if($test == -1){
				return -1;
			}
		}
		return 1;
	}
	
	public function parseLevel2(){
		$AD = new addressParser($this->r, $this->zip);
		if($this->result['zip'] != -1 and $this->result['city'] == -1){
			$this->result['city'] = $AD->cityFinderLevel2($this->result['zip']);
		}
		
		if($this->result['city'] != -1 and $this->result['street'] == -1){
			$this->result['street'] = $AD->streetFinderLevel2($this->result['city']);
		}
	
	}
	
	public function onController(){
		$this->parseLevel1();
		if($this->testLevel1() == -1){
			$this->parseLevel2();
		}
	}

}





class parseProbateAddress extends textParser{
	public function __construct($text, $zip){
		$this->zip = $zip;
		$this->text = $text;
		$this->onController();
	}
	
	function parseLevel1(){
		echo "<br>Probate Address1--->".$this->text;
		//$pos = strpos($this->text,"Relation")+strlen("Relation");
		$pos1 = $this->strposProbate($this->text,"Relation")+strlen("Relation");
		//echo __LINE__ . ": " . $pos1 . " ----  " . $pos2;
		$pos2 = strpos($this->text, "Address:", $pos1);
		$string = substr($this->text,$pos1, $pos2-$pos1);
		//echo "<br>Probate Address1--->".$string;
		
		$pos1 = $this->detectNumber($string);
		$string = substr($string,$pos1);
		//echo "<br>Probate Address2--->".$string;
		
		$AD  = $this->AD = new addressParser($string, $this->zip);
		$state = $AD->stateFinderLevel1();
		
		$zip 	= $AD->zipFinderLevel1();
		if($zip == -1){
			$zip   = $AD->zipFinderLevel2($state, $this->zip);
		}
		
		if($zip == -1){
			$city = -1;
			$street = -1;
		}else{
			$pos2 = strpos($string,$zip) + strlen($zip);
			$string = substr($string,0, $pos2);
			//echo "<br>Probate Address3--->".$string;
		
			$AD = new addressParser($string, $this->zip);
			$city  = $AD->cityFinderLevel2($zip);
			$street = $AD->streetFinderLevel2($city);
		}
		
		if($street == -1){
			$street = $AD->streetFinderLevel3($state);
		}
		
		$this->result['street'] = $street;
		$this->result['city'] 	= $city;
		$this->result['state'] = $state;
		$this->result['zip'] 	= $zip;
	}
	
	public function  testLevel1(){
		foreach($this->result as $key => $value){
			if($value == -1 and $key != 'zip'){
				return -1;
			}
		}
	}
	
	public function parseLevel2(){
		$AD = $this->AD;
		echo "<H5 style='color:purple'>Probate Address Parse Level 2</H5>";
		if($this->result['zip']  == -1){
			$this->result['zip']   = $AD->zipFinderLevel2($this->result['state'], $this->zip);
		}
			
		$city  = $AD->cityFinderLevel3($this->result['state'], $this->zip);
		$this->result['city'] = $city;
		$this->result['street'] = $AD->streetFinderLevel2($city);
		
		if($this->result['street'] == -1){
			$this->result['street'] = $AD->streetFinderLevel3($this->result['state']);
		}
	}
	
	public function testLevel2(){
		if($this->result['city'] == ""){return -1;}
		if($this->result['street'] == ""){return -1;}
	}

	public function onController(){
		$this->parseLevel1();
		if($this->testLevel1() == -1){
			if($this->result['state'] != -1){
				$this->parseLevel2();
			}
		}
	}
}



Class textParser{

	public function __construct($text, $ZIP){
		$this->zip = $ZIP;
		$this->text = $text;
	}

	public function nextWord($text,$pos){
		for($x=$pos;$x<=strlen($text);$x++){
			$c = substr($text,$x);
			if(ord($c)==32){
				return $x;
			}
		}
	
	}
	
	function match($string1, $string2, $threshold=.95){
		if(strlen($string1)>0 and strlen($string2)>0){
			$num = levenshtein($string1,$string2);
			$length = strlen($string1)<strlen($string2) ? strlen($string1) : strlen($string2);
			$match = 1- round(floatval($num/$length),2);
			if($match<$threshold and $threshold!= -1){
				return -1;
			}else if($threshold==-1){
				return $match;
			}else{
				return 1;
			}
		}else{
			return -1;
		}
	}
	
	function closeMatch($string ,$array){
		$string = strtolower($string);
		foreach($array as $key => $value){
				$value = strtolower($value);
				if( levenshtein($string, $value) <= 1){
					return $string;
				}
		}
		return -1;
	}
	
	function strposProbate($haystack,$string){
		$string = strtolower($string);
		$words = explode(" ", $haystack);
		$j = 0;
		$test = 0;
		$last = 0;
		foreach($words as $i => $word){
			$word = trim(strtolower($word));
			if($j == 0){
				$count = strlen($word);
				$j++;
			}else{
				$count = $count  + strlen($word) + 1;
			}		
			//echo "<br>COUNT-- $count  STRING--$string --- $word";
			if(levenshtein("type", $word)<= 1){$test++; $last = $i;}
			if(levenshtein("address:", $word)<= 1){$test++;$last = $i;}
			if(levenshtein("status" , $word)<= 1){$test++;$last = $i;}
				
			if($test >2){	
				return $count +1;
			}
			
			if($last < $i-3){
				$test = 0;
			}
		}
		return -1;
	}
	
	function proper($string){
		$string = strtolower($string);
		$cap  =  strtoupper(substr($string, 0, 1));
		$word = substr($string,1,strlen($string));
		return $cap . $word;
	}
	
	function lowerCase($string){
		$string = "";
		for($x=0;strlen($string);$x++){
			$l = substr($string,$x,1);
			if(ord($l)>=65 && ord($l)<=90){
				$string .= char(ord($l)+32);
			}else{
				$string .= $l;
			}
		}
		return $string;
	}
	
	public function detectNumber($string){
		for($x=0;$x<=strlen($string);$x++){
			$l = substr($string, $x);
			if(ord($l) >=48 and ord($l)<= 57){
				return $x;
			}
		}
		return -1;
	}
	
	public function explodeWord($string){
		$string  = trim($string);
		$words  = array();
		$word = "";
		for($x=0;$x<=strlen($string);$x++){
			$l = substr($string, $x,1);
			if(ord($l) == 32 || ord($l) == 160){
				$words[] = $word;
				$word = "";
			}else{
				$word .= $l;
			}
		}
		return $words;
	}
	

	public function parseProbate($text){
		$probateDate = new parseProbateDate($text);
		$deathDate = new parseDeathDate($text);
		$deceasedName = new parseDeceasedName($text);
		$deceasedAddress = new parseDeceasedAddress($text, $this->zip);
		$probateName = new parseProbateName($text);
		$probateAddress = new parseProbateAddress($text, $this->zip);
		

		/*echo "<br><H1>ProbateDate:" . $probateDate->result . "</H1>";
		echo "<br><H1>DeathDate:" . $deathDate->result . "</H1>";
		echo "<br><H1>DeceasedName:" . $deceasedName->result . "</H1>";
		
		$array = $deceasedAddress->result;
		$deceasedString = "";
		foreach($array as $i => $info){
			$deceasedString .= $info;
		}
		echo "<br><H1>DeceasedAddress" . $deceasedString. "</H1>";
		
		echo "<br><H1>ProbateName: " . $probateName->result . "</H1>";
		
		$array = $probateAddress->result;
		$probateString = "";
		foreach($array as $i => $info){
			$probateString .= $info;
		}
		echo "<br><H1>ProbateAddress" . $probateString . "</H1>";*/
		$this->out['CaseType'] = $this->caseType;
		$this->out['ProbateDate'] = $probateDate->result;
		$this->out['DateofDeath'] = $deathDate->result;
		$this->out['DecdFullNamePulled'] = $deceasedName->result;
		$this->out['DecdLastAddress'] = $deceasedAddress->result['street'];
		$this->out['DecdLastCity'] = $deceasedAddress->result['city'];
		$this->out['DecdLastState'] = $deceasedAddress->result['state'];
		$this->out['DecdLastZip'] = $deceasedAddress->result['zip'];
		$this->out['PRFullNamePulled'] = $probateName->result;
		$this->out['PRAddress'] = $probateAddress->result['street'];
		$this->out['PRCity'] = $probateAddress->result['city'];
		$this->out['PRState'] = $probateAddress->result['state'];
		$this->out['PRZip'] = $probateAddress->result['zip'];
		
	}
	
	public function parseNextofKin($text){
		$probateDate = new parseProbateDate($text);
		$deathDate = new parseDeathDate($text);
		$deceasedName = new parseDeceasedName($text);
		$deceasedAddress = new parseDeceasedAddress($text, $this->zip);
		$probateName = new parseProbateName($text, "Af");	
		$probateAddress = new parseProbateAddress($text, $this->zip);
		
		/*echo "<br><H1>ProbateDate:" . $probateDate->result . "</H1>";
		echo "<br><H1>DeathDate:" . $deathDate->result . "</H1>";
		echo "<br><H1>DeceasedName:" . $deceasedName->result . "</H1>";
		
		$array = $deceasedAddress->result;
		$deceasedString = "";
		foreach($array as $i => $info){
			$deceasedString .= $info;
		}
		echo "<br><H1>DeceasedAddress" . $deceasedString. "</H1>";
		
		echo "<br><H1>ProbateName: " . $probateName->result . "</H1>";
		
		$array = $probateAddress->result;
		$probateString = "";
		foreach($array as $i => $info){
			$probateString .= $info;
		}
		echo "<br><H1>ProbateAddress" . $probateString . "</H1>";*/
		$this->out['CaseType'] = $this->caseType;
		$this->out['ProbateDate'] = $probateDate->result;
		$this->out['DateofDeath'] = $deathDate->result;
		$this->out['DecdFullNamePulled'] = $deceasedName->result;
		$this->out['DecdLastAddress'] = $deceasedAddress->result['street'];
		$this->out['DecdLastCity'] = $deceasedAddress->result['city'];
		$this->out['DecdLastState'] = $deceasedAddress->result['state'];
		$this->out['DecdLastZip'] = $deceasedAddress->result['zip'];
		$this->out['PRFullNamePulled'] = $probateName->result;
		$this->out['PRAddress'] = $probateAddress->result['street'];
		$this->out['PRCity'] = $probateAddress->result['city'];
		$this->out['PRState'] = $probateAddress->result['state'];
		$this->out['PRZip'] = $probateAddress->result['zip'];
		
		
		
	}
	
	public function parseText(){
		$text = $this->text;
		$docket = new parseDocket($text);
				
		$caseType = new parseCaseType($text);
			
		echo "<br><H1>Docket:" . $docket->result . "</H1>";
		echo "<br><H1>CaseType:" . $caseType->result . "</H1>";	
		
		$this->out['ProbateType'] = "val";
		$this->out['Docket'] = $docket->result;
		
		switch($caseType->result){
			case "PROBATE":
				$this->caseType = "PROBATE";
				$this->parseProbate($text);
				break;
			case "NEXT OF KIN AFFIDAVIT":
				$this->caseType = "NEXT OF KIN AFFIDAVIT";
				$this->parseNextofKin($text);
				break;
			case "SPOUSE AFFIDAVIT":
				$this->caseType = "SPOUSE AFFIDAVIT";
				$this->parseNextofKin($text);
				break;
		}
			
	}



}



/*#### IN CASE LOSE ZIP CODES

-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2015 at 04:33 AM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT ;
!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS ;
!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION ;
!40101 SET NAMES utf8mb4 ;

--
-- Database: `temp`
--

-- --------------------------------------------------------

--
-- Table structure for table `zip`
--

CREATE TABLE IF NOT EXISTS `zip` (
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `primary_city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `acceptable_cities` text COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `county` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



LOAD DATA INFILE 'C:/Users/Owner/Documents/zip_code_database_file.csv'
Into table temp.zip
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
 (`zip`, `type`, `primary_city`, `acceptable_cities`,  `state`, `county`)
 
 */
?>