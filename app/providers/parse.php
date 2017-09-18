<?php
namespace app\providers{

Class parse extends \app\model{

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
	
	/*
	#WINDOWS
	public function convertToJPG($pdfFile, $nameTo){

		$s = trim("\ ");
		
		$convert =  "cd \ &&";//
		$convert .=  "cd Program Files\gs\gs9.16\bin &&";
		$convert .= "gswin32c.exe ";
		$convert .= "  -dGraphicsAlphaBits=4";
		$convert .= "  -sDEVICE=jpeg ";
		$convert .= " -dDEVICEWIDTHPOINTS=413 ";//413//826//1654
		$convert .= " -dDEVICEHEIGHTPOINTS=583 ";//583//1167//2333
		$convert .= " -dDEVICEXRESOLUTION=413 ";//413//826//1654
		$convert .= " -dDEVICEYRESOLUTION=583 ";//583//1167//2333
		$convert .= "  -sOutputFile=" . __DIR__ . $s . $nameTo . "  ";  //C:\xampp\htdocs\probates\app\outTEST%03d.jpeg"
		$convert .= __DIR__ .$s.$pdfFile;// "p1_APRIL2015_1.pdf"; //must have .pdf
		$convert .= "  -c quit ;";
		
		system($convert); // Execution of complete command.
	}*/

	#LINUX
	public function convertToJPG($pdfFile, $nameTo){

		$convert = "convert -density 413 -quality 100 ";
		$convert.= $pdfFile ." ";
		$convert.= $nameTo;
echo "\n\n".__LINE__."--convert-->".$convert;
		system($convert);
		exec("sudo chmod 777 resources -R");
	}


	/*
	#WINDOWS
	public function readJPG($imageFile){

		(new TesseractOCR($imageFile))
			->lang('eng')
			->run();

		$text = trim(file_get_contents(__DIR__."/stdout.txt"));
		unlink("stdout.txt");
	    return $text;

	}*/
	
	public function readJPG($imageFile){

		return (new \TesseractOCR($imageFile))
			->lang('eng')
			->run();
	}


	public function getZip(){
		$get = "SELECT * FROM probate.zip";
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
echo "\n\n".__LINE__."--imageFile-->".json_encode($imageFile,JSON_PRETTY_PRINT)."\n\n";
			echo "<h2 style='color:red'>This is the imageFile-->$imageFile</h2>";
			$this->convertToJPG($pdfFile, $imageFile);
			$text = $this->readJPG($imageFile);
echo "\n\n".__LINE__."--text-->".$text."\n\n";
			$out[$page] = $this->parse($text);
			//if($page==3){break;}
		}
echo "\n\n".__LINE__."--out-->".json_encode($out,JSON_PRETTY_PRINT);
		$this->array_to_CSV($out,  $this->masterFileName . "_out" );
	}
}
}