<?php

namespace App\Http\Controllers\JobControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\RunService as Run;

class ParseText extends Controller
{
	public function __construct($file){	

		### CACHE FILES ###
		$this->file = $file;
		Cache::put('file', $this->file, 30);
	}


	public function handle(){

		// Get the Number of Pages
		$pageCount = run::getPageCount($this->file);

		$out = array();

		// Iterate through each page
		for($page=1;$page<=$pageCount;$page++){

			### CACHE PAGE ###
			Cache::put('page',$page,10);

			### CACHE FILE NAMES ###
			Run::cachePageFile($this->file,$page);

			// If there is a text file in cache, use it
			if(is_file(Cache::get('textFile'))){
				#$text = Storage::get($txtFname);
				$text = file_get_contents(Cache::get('textFile'));

				//cache result-- the data will be used in parsing functions
				$out[$page] = run::parse($text);
			}
		}

		//Converts the object to a csv file
		run::array_to_CSV($out,  substr($this->file,0,-4) . "_out" );
	}
}
