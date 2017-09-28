<?php

namespace app\services{
	Class parseService{

		public static $out;
		private static $zip;
		private static $name;



		# Compares
		public static function compare($haystack,$needle){
			
			//Lists out what letters might be off
			$changes = array("l"=>"i");

			//Start with a case insentive strpos
			if(stripos($haystack,$needle) !== false){
				return stripos($haystack,$needle);
			}else{
			//If the strpos fail, the needle will be changed, and try again
				foreach($changes as $find => $replace){
					$haystack = str_replace($find,$replace, $haystack);
					if(stripos($haystack,$needle) !== false){
						return stripos($haystack,$needle);
					}
				}
			}

			return false;
		}


		# Converts an array to CSV
		public static function array_to_CSV($array, $filename){
			$keys	=	array_keys($array[1]);
			$f		=	fopen($filename.".csv" ,'w');
			fputcsv ($f	 , $keys);
			
			foreach($array as $i => $info){
				$info = preg_replace( "/\r|\n/", "", $info );
				fputcsv ($f	 , $info);
			}
			
			fclose($f);
		}

	}
}

/*	public function nextWord($text,$pos){
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
	}*/