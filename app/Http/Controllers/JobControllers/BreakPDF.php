<?php

namespace App\Http\Controllers\JobControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\RunService as Run;

class BreakPDF extends Controller{

	public function __construct($file){	

		### CACHE FILES ###
		$this->file = $file; //Getting proof of concept
		Cache::put('file',$file,20);
	}


	public function handle(){

		// Get the Number of Pages
		$pageCount = Run::getPageCount($this->file);

		// Iterate through each page
		for($page=1;$page<=$pageCount;$page++){

			### CACHE PAGE ###
			Cache::put('page',$page,10);

			### CACHE FILE NAMES ###
			Run::cachePageFile($this->file,$page);

			// break out the PDF by page
			Run::paginatePDF();
			
		}

		return $pageCount;
	}

}
