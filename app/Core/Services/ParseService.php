<?php

namespace App\Core\Services;

Class parseService{

	public static $out;
	private static $zip;
	private static $name;


	# Break Out Lines
	public static function breakLines($text){

		$lines = explode('\n',$text);

		$result = array();
		foreach($lines as $i => $line){
			if(strlen($line) > 0){
				array_push($result,$line);
			}
		}

		return $result;
	}


	# ###################################################################################
	# Generic keyword search function
	#	text - block of text getting parsed
	#	keyword - keyword getting searched
	#	scope - length of string to be returned. If null, it will return the line
	#	qLine - the lines to be examined.  
	#			array(start,end) -- based on index so the first value is 0
	# ###################################################################################
	public static function parseKeyWord($text,$keyword, $scope =null, $qLine = array()){

		$lines = self::breakLines($text);

		if(count($qLine)==0){
			$a = 0;
			$b = count($lines);
		}

		for($i=$a;$i<$b;$i++){

			// Set Line
        	$line = $lines[$i];

            // Remove Spaces
            $line = preg_replace('/\s+/',$line);
            $keyword = preg_replace('/\s+/',$keyword);

            // Get Position of Keyword
            $pos = stripos($line,$keyword);
            if($pos !== false){

                $pos = $pos + strlen($keyword);
                return substr($line,$pos,$scope);
            }
        }

        return false;
    }


	# ###################################################################################
    # Removes lines that are shorter than a certain length
    #
    # Text - the text from OCR
    # Length - if less than legth, remove line
	# ###################################################################################
    public function removeShortLines($text, $length){

    	$lines = self::breakLines($text);

    	$temp  = $lines;
    	foreach($lines as $i => $line){
    		if(strlen($line) < $length){
    			unset($temp[$i]);
    		}
    	}

    	return $temp;
    }



	# ###################################################################################
    # Find the line in the text file where the prbate information is...
	# ###################################################################################
	public static function findProbateLine($lines){
		
		foreach($lines as $i => $line){
			
			$line = preg_replace('/\s+/',$line);
			
			//Probate addres comes right after the line
			// that says 'TYPE Address:'
			if(stripos($line,'typeAddress:') !== false){
				return $i+1;
			}
		}
	}

	public static function getProbateLine($lines){
		
		$index = self::findProbateLine($lines);
		return $lines[$index];
	}


	# ###################################################################################
	# Checks to see if the string is propser case
	# ###################################################################################
	public static function checkProper($string){
		
		$ss = str_split($string);
		$a 	= strtoupper($ss[0]);

		if($a != $ss[0]){
			return false;
		}else{
			$word = array_slice($ss,1);
			foreach($word as $i => $l){
				$a = strtolower($l);
				if($a != $l){
					return false;
				}
			}
		}

		return true;
	}

	# ###################################################################################
	# Removes all word without a given case: UPPER, LOWER,or PROPER
	# ###################################################################################
	public static function removeCase($line, $case='PROPER'){

		// Break appart line
		$e = explode(' ',$line);

		$line = '';
		foreach($e as $i => $string){
			switch($case){

				case 'UPPER':

					if(strtoupper($string) == $string){ 
						$line.= $string.' ';
					}// test, if pass, add to line
					
					break;

				case 'LOWER':

					if(strtolower($string) == $string){
						$line.= $string.' ';
					}// test, if pass, add to line

					break;

				case 'PROPER':

					if(self::checkProper($string)){
						$line.= $string.' ';
					}

					break;
			}
		}

		if($line == ''){	return false;
		}else{				return $line;}
	}


	public static function findCaseIndex($line, $case='PROPER'){
		
		// Break appart line
		$e = explode(' ',$line);

		$line = '';
		foreach($e as $i => $string){
			switch($case){

				case 'UPPER':

					if(strtoupper($string) == $string){ 
						return $i;
					}// test, if pass, add to line

					break;

				case 'LOWER':

					if(strtolower($string) == $string){
						return $i;
					}// test, if pass, add to line

					break;

				case 'PROPER':

					if(self::checkProper($string)){
						return $i;
					}

					break;
			}
		}

		return false;

	}


	# ###################################################################################
	# Find the number
	# line - string to pase
	# worderNumbers - will check of spelled out numbers like 'One', 'Two', 'Three'... etc
	# ###################################################################################
	public static function findNumber($line, $wordedNumbers=false){

		$wordedNumbers = array('one','two','three','four','five','six','seven','eight','nine');

		$e = explode(' ',$line);

		foreach($e as $i => $a){
			if(		is_numeric($a) // if word is numeric
				||  ( $wordedNumbers === true 
					&& in_array(strtolower($a),$wordedNumbers)) 
				){
				
					return $i;
			}
		}

		return false;
	}


	# ###################################################################################
	# Slices up the in line between spaces a and b.
	# line- string to be parsed
	# a- start
	# b- end
	# ###################################################################################
	public static function sliceLine($line,$a,$b=null){

		$e = explode(' ',$line);

        if(is_null($b)){$b = count($e);}

		$line = '';
		for($i=$a;$i<$b;$i++){
			$line.=$e[$i].' ';
		}

		return trim($line);
	}

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