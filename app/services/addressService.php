<?PHP
namespace app\services{
	Class addressService{

		# Set Zip
		private static function setZip(){

			$get = "SELECT * FROM probate.zip";
			$data = $GLOBALS['connection']->select($get);
			
			$result = array();
			foreach($data as $i => $info){
				$result[$info['zip']]['city'] = $info['primary_city'];
				$result[$info['zip']]['city2'] = $info['acceptable_cities'];
				$result[$info['zip']]['state'] = $info['state'];
			}

			self::$zip = $result;
		}


		# Returns All the Zip Codes Across the US
		public static function getZip(){

			if(count(self::$zip) == 0){self::setZip();}

			return self::$zip;
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
			
			#echo "<H5 style='color:purple'>This is ZIP check" .  count($ZIP) , "</H5>";
			
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
			if(count($matches)==1){
				return $matches[0];
			}else{
				#echo "no matches found.";
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
}
?>