<?php

namespace App\Core\Services;
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