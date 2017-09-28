<?php

namespace app\services{
use \app\services\cacheService as cache;
	Class runService{

		public static function getPageCount($file){

			//New instance of the PDF reader
			$PDF = new \fpdi\FPDI();

			//This returns the page count
			return $PDF->setSourceFile($file);

		}


		# Takes a multipage PDF and breaks it out to individual pages
		public static function paginatePDF($file,$page){

			# New instance
			$PDF = new \fpdi\FPDI();
			
			# Sets the source
			$PDF->setSourceFile($file);
			
			# Create the page
			$PDF->AddPage();
			$tplIdx = $PDF->importPage($page);
			$PDF->useTemplate($tplIdx);

			// Gets the actual file name
			$e = explode("/",$file);
			$fname = substr(end($e),0,-4);
			
			# Cache Files
			$pdfFile = root."/storage/cache/{$fname}_p{$page}.pdf";
			cache::cachePdfFile($pdfFile);

			# Render file
			$PDF->output($pdfFile,'F');
			
			# Return file source
			return $pdfFile;
		}
		
		# Converts PDF to a JPEG
		public static function convertToJPG($pdfFile, $nameTo , $density = 413, $quality = 100){

			# Build the command
			$convert = "convert -density ". $density ." -quality " . $quality ." ";
			$convert.= $pdfFile ." ";
			$convert.= $nameTo;
echo "\n\n\--".__LINE__."--convert-->".$convert;
			# Execute command
			exec($convert);

			# Change the permissions of the folder
			exec("sudo chmod 777 ".root."/storage -R");
		}

		public static function unlinkPage($page){

			$fname = cache::getCache("fname");

			$jpgFile = root."/storage/cache/{$fname}_p{$page}.jpg";
			$txtFile = root."/storage/cache/{$fname}_p{$page}.txt";

			@unlink($jpgFile);
			@unlink($txtFile);
		}

		# Reads the JPG
		public static function readJPG($imageFile){

			cache::cacheImageFile($imageFile);

			return (new \TesseractOCR($imageFile))
				->lang('eng')
				->run();
		}


		#Parse Text
		public static function parse($text){

			exec("sudo chmod 777 ".root."/storage -R");

			$P = new \app\parseController($text);

			return  $P->onController();
		}


		# Converts an array to CSV
		public static function array_to_CSV($array, $filename){

			exec("sudo chmod 777 storage -R");
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