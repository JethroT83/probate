<?php
require_once(__DIR__."/global.php");
//require_once("../vendor/autoload.php");
#require_once(__DIR__."/../vendor/tesseract-ocr-for-php-master/TesseractOCR/TesseractOCR.php");
require_once(__DIR__."/../vendor/autoload.php");
require_once(__DIR__."/textParser.php");
set_time_limit(120000);
ini_set('memory_limit','2048M');

//https://github.com/thiagoalessio/tesseract-ocr-for-php
Class parse extends global_ {

	public function __construct($file){	

		$this->masterFile = $file;
		$this->masterFileName = substr($file,0,strlen($file)-4);
		$this->PDF = new \fpdi\FPDI();
		$this->pageCount = $this->PDF->setSourceFile($file);
		$this->pdfFiles = array();
		$this->onController();
	}
	
	public function paginatePDF($page){
		$this->PDF = new \fpdi\FPDI();
		$this->PDF->setSourceFile($this->masterFile);
		$this->PDF->AddPage();
		$this->PDF->setSourceFile($this->masterFile);
		$tplIdx = $this->PDF->importPage($page);
		$this->PDF->useTemplate($tplIdx);
		//$folder = "app/". date('MY') . "/";
		$folder = "";
		//$f = $this->pdfFiles[count($this->pdfFiles)] = "$folder"."p"."$page"."_{$this->masterFile}";
		$f = $this->pdfFiles[count($this->pdfFiles)] = "$folder{$this->masterFileName}_p{$page}.pdf";
		$this->PDF->output($f,'F');
		return $f;
	}
	
	public function convertToJPG($pdfFile, $nameTo){
		//$location   = "C:\Program Files\ImageMagick-6.9.1-Q16"; // Binaries Location
		//$location   = "convert";
		//$convert    = $location . " " . $pdfFile . " ".$nameTo; // Command creating
		//echo "<h1>This is convert---->$convert</h1>";
		
		$s = trim("\ ");
		
		$convert =  "cd \ &&";//
		$convert .=  "cd Program Files\gs\gs9.16\bin &&";
		$convert .= "gswin32c.exe ";
		//$convert .= "  -dMaxBitmap=500000000 ";
		$convert .= "  -dGraphicsAlphaBits=4";
		$convert .= "  -sDEVICE=jpeg ";
		/*$convert .= " -dDEVICEWIDTHPOINTS=207 ";//413//826//1654
		$convert .= " -dDEVICEHEIGHTPOINTS=292 ";//583//1167//2333
		$convert .= " -dDEVICEXRESOLUTION=207 ";//413//826//1654
		$convert .= " -dDEVICEYRESOLUTION=292 ";//583//1167//2333*/
		$convert .= " -dDEVICEWIDTHPOINTS=413 ";//413//826//1654
		$convert .= " -dDEVICEHEIGHTPOINTS=583 ";//583//1167//2333
		$convert .= " -dDEVICEXRESOLUTION=413 ";//413//826//1654
		$convert .= " -dDEVICEYRESOLUTION=583 ";//583//1167//2333
		$convert .= "  -sOutputFile=" . __DIR__ . $s . $nameTo . "  ";  //C:\xampp\htdocs\probates\app\outTEST%03d.jpeg"
		$convert .= __DIR__ .$s.$pdfFile;// "p1_APRIL2015_1.pdf"; //must have .pdf
		$convert .= "  -c quit ;";
		
		echo $convert;
		system($convert); // Execution of complete command.
	}
	
	public function readJPG($imageFile){
#echo "\n\n-".__LINE__."--imageFile-->".$imageFile;
		(new TesseractOCR($imageFile))
			->lang('eng')
			#->tessdataDir(__DIR__)
			->run();
		#$OCR->tessdataDir(__DIR__);
		#$OCR->lang('eng');
		//$this->OCR->setWhitelist(range(0,9));		
		#return echo ($OCR->run());
		$text = trim(file_get_contents(__DIR__."/stdout.txt"));
		unlink("stdout.txt");
	    return $text;

	}
	
	public function getZip(){
		$get = "SELECT * FROM temp.zip";
		$this->read(1,$get, __FILE__, __LINE__);
		
		foreach($this->result as $i => $info){
			$this->zip[$info['zip']]['city'] = $info['primary_city'];
			$this->zip[$info['zip']]['city2'] = $info['acceptable_cities'];
			$this->zip[$info['zip']]['state'] = $info['state'];
		}
	}
	
	public function parse($text){
		$P = new textParser($text, $this->zip);
		$P->parseText();
		return $P->out;
	}

	public function onController(){
			$this->getZip();
			echo "\n\nThis is page count -->". $this->pageCount;
		for($page=1;$page<=$this->pageCount;$page++){
			echo "<h2 style='color:red'>$page</h2>";
			$pdfFile = $this->paginatePDF($page);
			$imageFile = "{$this->masterFileName}_p{$page}.jpg";
			echo "<h2 style='color:red'>This is the imageFile-->$imageFile</h2>";
			$this->convertToJPG($pdfFile, $imageFile);
			$text = $this->readJPG($imageFile);
echo "\n\n".__LINE__."--text-->".json_encode($text,JSON_PRETTY_PRINT);
			$out[$page] = $this->parse($text);
			//if($page==3){break;}
		}
echo "\n\n".__LINE__."--out-->".json_encode($out,JSON_PRETTY_PRINT);
		$this->array_to_CSV($out,  $this->masterFileName . "_out" );
	}
}

	$file = "build.pdf";
	$P =  new parse($file);