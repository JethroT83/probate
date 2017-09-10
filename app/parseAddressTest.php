<?php
require_once("global.php");

Class  Zipget extends global_{
	public function getZip(){
		$get = "SELECT * FROM temp.zip";
		$this->read(1,$get, __FILE__, __LINE__);
		
		foreach($this->result as $i => $info){
			$this->zip[$info['zip']]['city'] = $info['primary_city'];
			$this->zip[$info['zip']]['city2'] = $info['acceptable_cities'];
			$this->zip[$info['zip']]['state'] = $info['state'];
		}
	}
}

Class addressParser{
	public function __construct($result, $zip){
		$this->zip = $zip;
		$this->result = $result;
		echo "This is result-->" . $this->result;
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
		if(stripos($this->result, $city) !== false){
			$pos = strpos($this->result, $city);
			return substr($this->result, 0, $pos);
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
		$endings = array("township","borough");
		$result  = strtolower($this->result);
		$cityQ = array();
		
		$city = $this->zip[$zip]['city'];
		$cityString = $this->zip[$zip]['city2'];
		$cities = explode(",",$cityString);
		$cities[count($cities)] = $city;
		
		foreach($cities as $i => $city){
			$city = strtolower($city);
			$city = str_replace($endings, "",$city);
			
			if(stripos($city, $result) !== false){
				$cityQ[] = $this->proper($city);
			}
		}
		
		if(count($cityQ) == 1){
			return $cityQ[0];
		}else{
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
		
		foreach($stateQ as $i => $state){
			if(array_search($state, $states)!==false){
				$i = array_search($state, $states);
				$s[] = $states[$i];
			}
		}
		
		if(count($s) == 1){
			return $s[0];
		}else{
			return -1;
		}
	}

	
	//## ZIP
	public function zipFinderLevel1(){
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
	
}

	$string = "44 Brookside Avenue, Ocean Township, NJ 07712";
	
	$Z = new Zipget;
	$Z->getZip();
	
	$AP = new addressParser($string, $Z->zip);
	echo $AP->countCommas();
	
	


?>