<?php

namespace app\services{
	
	Class cacheService{



	##########################################################
	################	 	SET CACHE 		##################
	##########################################################

		## "root" is the directory
		private static $root;

		public static function cacheRoot($root){
			self::$root;
		}

		## "file" is the file the app is parsing
		private static $file;
		private static $fname;

		public static function cacheFile($file){
			self::$file;

			// Gets the actual file name
			$e = explode("/",$file);
			self::$fname = substr(end($e),0,-4);

		}


		## "page" is the page the app current parsing through
		private static $page;

		public static function cachePage($page){
			self::$page = $page;
		}

		## "pdfFile" points the current PDF getting processed
		private static $pdfFile;

		public static function cachePdfFile($pdfFile){
			self::$pdfFile = $pdfFile;
		}

		## "imageFile" points to the current JPG getting processed
		private static $imageFile;

		public static function cacheImageFile($imageFile){
			self::$imageFile = $imageFile;
		} 


		## "out" is the main payload
		private static $out;

		public static function cacheOut($data, $page){
			self::$out[$page] = $data;
		}



	##########################################################
	################	 RETRIEVE CACHE 	##################
	##########################################################

		## getCache is retrives the cache ##
		public static function getCache($name){

			switch($name){

				case "file": 	return self::$file;

				case "fname": 	return self::$fname;

				case "page":
					return self::$page;

				case "pdfFile":
					return self::$pdfFile;

				case "imageFile":
					return self::$imageFile;

				case "out":
					return self::$out;

			}
		}




	}
}