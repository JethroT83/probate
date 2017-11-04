<?php

namespace App\Core\Services;
use Illuminate\Support\Facades\Storage;

use \App\Core\Services\ParseService as Parse;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\DB;
Class RunService{


	public static function cacheFile(int $fileID){

		$info = self::getFileInfo($fileID);
		Cache::put('file_info',$info,20);

		$file = base_path()."/storage/app/uploads/".$info->local_name;
		Cache::put('file',$file,20);

	}


	public static function cachePageFile($file,$page){

		$e = explode("/",$file);
		$fname = substr(end($e),0,-4);
		Cache::put('fname', $file, 30);

		### CACHE PAGES ###
		$pdfFile = base_path()."/storage/app/uploads/{$fname}_p{$page}.pdf";
		Cache::put('pdfFile',$pdfFile,20);

		$imageFile = base_path()."/storage/app/uploads/{$fname}_p{$page}.jpg";
		Cache::put('imageFile',$imageFile,20);

		$textFile = base_path()."/storage/app/uploads/{$fname}_p{$page}.txt";
		Cache::put('textFile',$textFile,20);

	}


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
	public static function convertToJPG($density = 600, $quality = 100){

		$pdfFile 	= Cache::get('pdfFile');
		$imageFile 	= Cache::get('imageFile');

		# Build the command
		$cmd = "convert -density ". $density ." -quality " . $quality ." ";
		$cmd.= $pdfFile ." ";
		$cmd.= $imageFile;

		# Execute command
		exec($cmd);
	}

	public static function unlinkPage($page){

		$fname = cache::getCache("fname");

		$jpgFile = base_path()."/storage/cache/{$fname}_p{$page}.jpg";
		$txtFile = base_path()."/storage/cache/{$fname}_p{$page}.txt";

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

		return shell_exec($cmd);

	}


	#Parse Text
	public static function parse($text){

		$P = new \App\Http\Controllers\ParseController($text);

		return  $P->handle();
	}

	public static function getFileInfo($fileID){

		$data = DB::table('files')
				->where('id',$fileID)
				->get();

		if(count($data) == 0){
			error_log("No file by the id of ".$fileID);
			die();
		}else{
			return $data[0];
		}

	}


	public static function postText($fileID, $info){

		$info = Parse::sanitizeLines($info); // Removes linebreaks

		DB::table('report')->insert(
		    [
				'file_id' 			=> $fileID,
				'docket' 			=> $info['Docket'],
				'case_type'			=> $info['CaseType'],
				'probate_date'		=> $info['ProbateDate'],
				'death_date'		=> $info['DateofDeath'],
				'deceased_name'		=> $info['DecdFullNamePulled'],
				'deceased_address'	=> $info['DecdLastAddress'],
				'deceased_city'		=> $info['DecdLastCity'],
				'deceased_state'	=> $info['DecdLastState'],
				'deceased_zip'		=> $info['DecdLastState'],
				'probate_name'		=> $info['PRFullNamePulled'],
				'probate_address'	=> $info['PRAddress'],
				'probate_city'		=> $info['PRCity'],
				'probate_state' 	=> $info['PRState'],
				'proabte_zip'		=> $info['PRZip'],
				'input_date' 		=> date('Y-m-d')
		    ]
		);
	}


	public static function getReportByFile($fileID){

		return DB::table('report')
			->select('input_date','docket','case_type','probate_date','death_date','deceased_name',
					'deceased_address','deceased_city','deceased_state','deceased_zip','probate_name',
					'probate_address','probate_city','probate_state','proabte_zip')
			->where('file_id',$fileID)
			->get();

	}

	public static function getCsvByFile($fileID){

		$data = self::getReportByFile($fileID);
		$file = self::getFileInfo($fileID);

		if(!empty($data) && $file !== false){

			$keys	=	array_keys($data[0]);
			$fName  = 	str_replace(".pdf","",$file['frontend_name']);
			$f		=	fopen($fName.".csv" ,'w');
			fputcsv ($f, $keys);
			
			foreach($data as $i => $info){
				fputcsv($f, $info);
			}
			
			fclose($f);

		}else{

			return false;
		}

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