<?php
namespace test {

	class serviceSetup{

		private static $dst;
		private static $dir;
		private static $src;
		private static $t;

		# Basic Unit Setup
		public static function setUpUnit(){

			# Get the data set from the environmental variable t
			self::$t = getenv("t");

			# If no environmental variable is set, set t=1
			if(!isset(self::$t)){self::$t=1;}

			self::$dir  = __DIR__."/dataSet_".self::$t;
			self::$src  = self::$dir."/build/txt";
			self::$dst  = __DIR__."/../storage/cache";
		}


		# Copies all the contents of a directory to another 
		public static function  copy_files() { 
			
			#Copy the build.pdf
			copy(self::$dir."/build/build.pdf",__DIR__."/../storage/build.pdf");

			# Copies all text files
	    	$dir = opendir(self::$src); 
			@mkdir(self::$dst); 
			while(false !== ( $file = readdir($dir)) ) { 
				if (( $file != '.' ) && ( $file != '..' )) { 
					if ( is_dir(self::$src . '/' . $file) ) { 
					    recurse_copy(self::$src . '/' . $file,$dst . '/' . $file); 
					} 
					else { 
					    copy(self::$src . '/' . $file,self::$dst . '/' . $file); 
					} 
				} 
			} 
			closedir($dir);
			#pause();
		} 
	
		# Retrives the Controlled variables from the data set folder
		public static function getControl(){
			return json_decode(file_get_contents(self::$dir."/control/control.json"),true);
		}

		# Retrieves the test variables from the app
		public static function getTest(){
			# Open CSV file the app outputs
			$rows = array_map('str_getcsv', file(__DIR__."/../storage/build_out.csv"));

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
}

?>