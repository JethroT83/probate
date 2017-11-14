<?php

namespace App\Http\Controllers\JobControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\RunService as Run;

class ConvertToText extends Controller
{
	
	protected $page;
	protected $file;

	public function __construct(int $page){	

		$this->page = $page;

		### CACHE PAGE ###
		Cache::put('page',$page,10);

	}


	public function handle(){

		$this->file = Cache::get('file');

		### CACHE FILE NAMES ###
		Run::cachePageFile($this->file,$this->page);


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
