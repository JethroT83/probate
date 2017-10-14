<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache as Cache;
use \App\Core\Services\RunService as run;

class RunController extends Controller{

	public function __construct(){	

		### CACHE FILES ###
		$this->file = ROOT."storage/app/OCT2017_6.pdf"; //Getting proof of concept
		Cache::put('file', $this->file, 30);

		// Gets the actual file name
		$e = explode("/",$this->file);
		$this->fname = substr(end($e),0,-4);
		Cache::put('fname', $this->file, 30);
	}


	public function handle(){

		// Get the Number of Pages
		$pageCount = run::getPageCount($this->file);
echo "<h1>Page Count:".$pageCount."</h1>";
		// Iterate through each page
		for($page=1;$page<=$pageCount;$page++){

			### CACHE PAGES ###
			//Cache Page
			Cache::put('page',$page,10);

			### CACHE PAGES ###
			$pdfFile = ROOT."storage/app/{$this->fname}_p{$page}.pdf";
			Cache::put('pdfFile',$pdfFile,20);

			$imageFile = ROOT."storage/app/{$this->fname}_p{$page}.jpg";
			Cache::put('imageFile',$imageFile,20);

			$textFile = ROOT."storage/app/{$this->fname}_p{$page}.txt";
			#$txtFname = "/var/www/probate/storage/app/{$this->fname}_p{$page}.txt";
			

			Cache::put('textFile',$textFile,20);

echo "<li>".$page."</li>";

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
