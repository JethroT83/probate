<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache as Cache;
use \App\Core\Services\RunService as run;

class RunController extends Controller{

	public function __construct(){	

		### CACHE FILES ###
		$this->file = base_path()."/storage/app/build.pdf"; //Getting proof of concept
		Cache::put('file', $this->file, 30);

	}


	public function handle(){

		// Get the Number of Pages
		$pageCount = run::getPageCount($this->file);

		// Iterate through each page
		for($page=1;$page<=$pageCount;$page++){

			### CACHE PAGE ###
			Cache::put('page',$page,10);

			### CACHE FILE NAMES ###
			Run::cachePageFile($this->file,$page);

			$pdfFile  	= Cache::get('pdfFile');
			$imageFile 	= Cache::get('imageFile');
			$textFile 	= Cache::get('textFile');

			// If there is a text file in cache, use it
			if(is_file($textFile)){
				#$text = Storage::get($txtFname);
				$text = file_get_contents($textFile);
			}else{
				if(!is_file($pdfFile)){
					// break out the PDF page
					run::paginatePDF();
				}

				if(!is_file($imageFile)){
					// Convert PDF to JPEG -- TERRERACT OCR cannot read PDFs
					run::convertToJPG();
				}

				// Converts JPEG to a text file
				$text = run::readJPG();
			}

			//cache result-- the data will be used in parsing functions
			$out[$page] = run::parse($text);
		}

		//Converts the object to a csv file
		run::array_to_CSV($out,  substr($this->file,0,-4) . "_out" );
	}
}
