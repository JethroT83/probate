<?php
namespace app{
use \app\services\runService as run;
use \app\services\cacheService as cache;
	Class runController{


		public function __construct($file){	

			// Set file name
			$this->file = $file;

			cache::cacheFile($file);

			// Gets the actual file name
			$e = explode("/",$file);
			$this->fname = substr(end($e),0,-4);

			//Runs the Code
			$this->onController();
		}


		public function onController(){

			// Get the Number of Pages
			$pageCount = run::getPageCount($this->file);

			// Iterate through each page
			for($page=1;$page<=$pageCount;$page++){

				//Cache Page
				cache::cachePage($page);

				// Pointer to the text file
				$textFile = root."/storage/cache/{$this->fname}_p{$page}.txt";

				// If there is a text file in cache, use it
				if(is_file($textFile)){
					$text = file_get_contents($textFile);
				}else{

					// break out the PDF page
					$pdfFile = run::paginatePDF($this->file, $page);

					// Convert PDF to JPEG -- TERRERACT OCR cannot read PDFs
					$imageFile = root."/storage/cache/{$this->fname}_p{$page}.jpg";
					run::convertToJPG($pdfFile, $imageFile);

					// Converts JPEG to a text filef
					$text = run::readJPG($imageFile);

					// Saves contents to the cache directory
					file_put_contents($textFile, $text);
				}
				
				//cache result-- the data will be used in parsing functions
				cache::cacheOut(run::parse($text),$page);
			}

			//Converts the object to a csv file
			run::array_to_CSV(cache::getCache("out"),  substr($this->file,0,-4) . "_out" );
		}
	}
}

