<?php
namespace App\Core\Services;

class DateService{

	public static function getProbateDateLine($lines=array()){

		foreach($lines as $i => $line){
			$line = preg_replace("/\s+/","",$line);
			
			// The OCR is never perfect, therefore this function will look for several things
			if(	   stripos($line,"dateofbirth:") 	!== false
				|| stripos($line,"filedate:") 		!== false
				|| stripos($line,"dateofdeath:") 	!== false){
				
				return $line;
			}
		}
	}
}

?>