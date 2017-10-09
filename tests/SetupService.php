<?php
namespace Tests;


class SetupService{

	private static $pdfFile;
	private static $controlFile;
	private static $txtDir;
	private static $cacheDir;
	private static $t;

	# Basic Unit Setup
	public static function setUpUnit(){

		@DEFINE("ROOT",__DIR__."/../"); // Define root directory

		if(is_null(getenv("t"))){self::$t=1;}//If no environmental variable is set, set t=1
		else{		self::$t = getenv("t");}//Get the data set from the environmental variable t

		$dir = "dataSet_".self::$t;
		self::$pdfFile  	= ROOT."storage/framework/testing/{$dir}/build/build.pdf";
		self::$controlFile  = ROOT."storage/framework/testing/{$dir}/control/control.json";
		self::$txtDir  		= ROOT."storage/framework/testing/{$dir}/build/txt";
		self::$cacheDir  	= ROOT."storage/app";
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
		return json_decode(file_get_contents(self::$controlFile),true);
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

	#Deletes all the contents in a directory
	public static function unlink_files(){

		#array_map('unlink', glob(self::$dst."/*"));
		#unlink(__DIR__."/../storage/build_out.csv");
		#unlink(__DIR__."/../storage/build.pdf");

	}
}


?>