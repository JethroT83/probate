<?php

namespace App\Http\Controllers\JobControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\RunService as Run;

class ConvertToText extends Controller
{
	public function __construct($file){	

		### CACHE FILES ###
		$this->file = $file; //Getting proof of concept
		Cache::put('file',$file,20);
	}


	public function handle(){

		// Get the Number of Pages
		$pageCount = run::getPageCount($this->file);

		for($page=1;$page<=$pageCount;$page++){

			### CACHE PAGE ###
			Cache::put('page',$page,10);

			### CACHE FILE NAMES ###
			Run::cachePageFile($this->file,$page);


			if(!is_file(Cache::get('imageFile'))){
				// Convert PDF to JPEG -- TERRERACT OCR cannot read PDFs
				run::convertToJPG();
			}

			if(!is_file(Cache::get('textFile'))){
				// Converts JPEG to a text file
				run::readJPG();
			}

		}

	}
}
