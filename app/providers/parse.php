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
		$folder = "";
		$f = $this->pdfFiles[count($this->pdfFiles)] = "$folder{$this->masterFileName}_p{$page}.pdf";
		$this->PDF->output($f,'F');
		return $f;
	}
	

	public function convertToJPG($pdfFile, $nameTo){

		$convert = "convert -density 413 -quality 100 ";
		$convert.= $pdfFile ." ";
		$convert.= $nameTo;

		system($convert);
		exec("sudo chmod 777 resources -R");
	}


	public function readJPG($imageFile){

		return (new \TesseractOCR($imageFile))
			->lang('eng')
			->run();
	}


	public function getZip(){
		$get = "SELECT * FROM probate.zip";
		$this->result = $GLOBALS['connection']->select($get);
		
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
			$out[$page] = $this->parse($text);
		}
		$this->array_to_CSV($out,  $this->masterFileName . "_out" );
	}
}
}