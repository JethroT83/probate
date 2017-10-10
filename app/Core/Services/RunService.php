<?php

namespace App\Core\Services;
use Illuminate\Support\Facades\Storage;

#use \app\services\cacheService as cache;
use Illuminate\Support\Facades\Cache;
Class runService{

	public static function getPageCount($file){

		//New instance of the PDF reader
		$PDF = new \fpdi\FPDI();

		//This returns the page count
		return $PDF->setSourceFile($file);

	}


	# Takes a multipage PDF and breaks it out to individual pages
	public static function paginatePDF(){

		#Get File
		$file = Cache::get('file');

		#Get Page
		$page = Cache::get('page');

		# New instance
		$PDF = new \fpdi\FPDI();
		
		# Sets the source
		$PDF->setSourceFile($file);
		
		# Create the page
		$PDF->AddPage();
		$tplIdx = $PDF->importPage($page);
		$PDF->useTemplate($tplIdx);

		# Render file
		$pdfFile = Cache::get('pdfFile');
		$PDF->output($pdfFile,'F');
		
	}
	
	# Converts PDF to a JPEG
	public static function convertToJPG($density = 413, $quality = 100){

		$pdfFile 	= Cache::get('pdfFile');
		$imageFile 	= Cache::get('imageFile');

		# Build the command
		$cmd = "convert -density ". $density ." -quality " . $quality ." ";
		$cmd.= $pdfFile ." ";
		$cmd.= $imageFile;

		# Execute command
		exec($cmd);

		# Change the permissions of the folder
		#exec("sudo chmod 777 ".ROOT."/storage -R");
	}

	public static function unlinkPage($page){

		$fname = cache::getCache("fname");

		$jpgFile = ROOT."storage/cache/{$fname}_p{$page}.jpg";
		$txtFile = ROOT."storage/cache/{$fname}_p{$page}.txt";

		@unlink($jpgFile);
		@unlink($txtFile);
	}

	# Reads the JPG
	public static function readJPG(){

		$imageFile = Cache::get('imageFile');
		$textFile  = Cache::get('textFile');

		$cmd = "tesseract ";
		$cmd.= $imageFile . " ";
		$cmd.= substr($textFile,0,-4) . " ";//Tesseract already puts a .txt when converting the file
		$cmd.= "-l eng ";
		#$cmd.= key space
#file_put_contents("cmd.txt",$cmd);
		shell_exec($cmd);

	}


	#Parse Text
	public static function parse($text){

		#exec("sudo chmod 777 ".ROOT."/storage -R");

		$P = new \App\Http\Controllers\ParseController($text);

		return  $P->onController();
	}


	# Converts an array to CSV
	public static function array_to_CSV($array, $filename){

		#exec("sudo chmod 777 storage -R");
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