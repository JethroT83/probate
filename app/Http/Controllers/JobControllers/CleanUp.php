<?php

namespace App\Http\Controllers\JobControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache as Cache;
use App\Core\Services\RunService as Run;

class CleanUp extends Controller
{
	public function __construct($file){	

		### CACHE FILES ###
		$this->file = $file; //Getting proof of concept
		Cache::put('file',$file,20);
	}


	public function handle(){
		// Get the Number of Pages
		$pageCount = Run::getPageCount($this->file);

		$pdfFile  	= Cache::get('pdfFile');
		$imageFile 	= Cache::get('imageFile');
		$textFile 	= Cache::get('textFile');


		for($page=1;$page<=$pageCount;$page++){
			
			### CACHE PAGES ###
			Run::cachePageFile($this->file,$page);

			if(is_file($pdfFile)){@unlink($pdfFile);}

			if(is_file($imageFile)){@unlink($imageFile);}

			if(is_file($textFile)){@unlink($textFile);}

		}

	}
}
