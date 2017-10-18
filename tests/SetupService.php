<?php
namespace Tests;


class SetupService{

	private static $pdfFile;
	private static $controlFile;
	private static $jobFile;
	private static $control;
	private static $txtDir;
	private static $cacheDir;
	private static $t;
	private static $p;

	# Basic Unit Setup
	public static function setUpUnit(){

		# Threshold
		if(getenv("p") == null){self::$p=90;}//If no environmental variable is set, set p=90
		else{		self::$p = getenv("p");}//Get the data set from the environmental variable p

		# DataSet
		if(getenv("t") == null){self::$t=1;}//If no environmental variable is set, set t=1
		else{		self::$t = getenv("t");}//Get the data set from the environmental variable t

		$dir = "dataSet_".self::$t;
		self::$pdfFile  	= base_path()."/storage/framework/testing/{$dir}/build/build.pdf";
		self::$controlFile  = base_path()."/storage/framework/testing/{$dir}/control/control.json";
		self::$jobFile  	= base_path()."/storage/framework/testing/{$dir}/control/job.json";
		self::$txtDir  		= base_path()."/storage/framework/testing/{$dir}/build/txt";
		self::$cacheDir  	= base_path()."/storage/app";
	}


	# Copies all the contents of a directory to another 
	public static function copy_files(){ 

		copy(self::$pdfFile,self::$cacheDir."/build.pdf");

		# Copies all text files
    	$dir = opendir(self::$txtDir); 
 
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir(self::$txtDir . '/' . $file) ) { 
				    recurse_copy(self::$txtDir . '/' . $file,self::$cacheDir . '/' . $file); 
				} 
				else { 
				    copy(self::$txtDir . '/' . $file,self::$cacheDir . '/' . $file); 
				} 
			} 
		}

		closedir($dir);
	} 

	# Retrives the Controlled variables from the data set folder
	public static function getControl(){
		return self::$control =  json_decode(file_get_contents(self::$controlFile),true);
	}

	# Retrives the Controlled variables from the data set folder
	public static function getJob(){
		return json_decode(file_get_contents(self::$jobFile),true);
	}

	# Retrieves the test variables from the app
	public static function getTest(){
		# Open CSV file the app outputs
		$rows = array_map('str_getcsv', file(self::$cacheDir."/build_out.csv"));

		# Bind the values to the keys
		foreach( $rows as $key => $value ) {
			//Error is suppressed to see exactly where the test fails
			$rows[$key] = @array_combine($rows[0], $value); 
		}
		
		# Omit the first row, which are the keys
		$rows = array_values(array_slice($rows,1));
		
		# Set the key of each row to the primary key field, which is the 'Docket'
		$result = array();
		foreach($rows as $i => $row) {
			$result[$row['Docket']] = $row;
		}

		return $result;
	}

	public static function grade($exceptions = array()){

		if(count($exceptions) == 0){	$grade = 100; // If there are no exceptions, the grade is 100
		}else{							$grade = (1 - count($exceptions)/count(self::$control)) * 100;}

		if($grade > self::$p){	$test = true; // If the grade is above the threshold, the passes
		}else{					$test = false;} // If grade is below the threshold, then it will fail

		echo "\n".$grade;
		return $test;
	}


	public static function getMessage($exceptions=array(),$cValue=array(),$tValue=array()){

		$message = "Tests exceptions have exceeded the threshold. ";
		$message.= "\nFailed docket numbers: ".json_encode($exceptions,JSON_PRETTY_PRINT);
		$message.= "\nControl values: ".json_encode($cValue, JSON_PRETTY_PRINT);
		$message.= "\nTest values: ".json_encode($tValue,JSON_PRETTY_PRINT);

		return $message;
	}


	#Deletes all the contents in a directory
	public static function unlink_files(){

		#array_map('unlink', glob(self::$dst."/*"));
		#unlink(__DIR__."/../storage/build_out.csv");
		#unlink(__DIR__."/../storage/build.pdf");

	}
}


?>