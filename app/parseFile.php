<?php
require_once("global.php");
require_once("../vendor/autoload.php");
set_time_limit(1200);

Class parse extends global_ {

	public function __construct($file){
		$this->OCR = new \Smalot\PdfParser\Parser();
		$this->PDF = new \fpdi\FPDI();
		$this->pageCount = $this->PDF->setSourceFile($file);
		//$this->pageCount = 3;
		$this->file = $file;
		$this->files = array();
		$this->onController();
	}
	
	
	public function openPDF($page){
		$this->PDF->AddPage();
		$this->PDF->setSourceFile($this->file);
		$tplIdx = $this->PDF->importPage($page);
		$this->PDF->useTemplate($tplIdx);
		$folder = "app/". date('MY') . "/";
		$folder = "";
		//echo $folder;
		$f = $this->files[count($this->files)] = "$folder"."p"."$page"."_{$this->file}";
		echo "This is the file--->" . $f;
		$this->PDF->output($f,'F');
	}
	
	function readFiles(){
		echo "<br><br><br>These are the files-->";
		var_dump($this->files);
		echo "<br>";
		foreach($this->files as $i => $file){
			echo "<br>This is file--->".$file;
			//$pdf = $this->OCR->parseFile($file);
			$pdf = $this->OCR->parseFile("p1_APRIL2015_1.pdf");
			//echo "<br><br>" . var_dump($this->OCR);
			/*$pages  = $pdf->getPages();
			foreach($pages as $page){
				$text = $page->getText();
				print_R($text);
			}*/
			var_dump($pdf->getText());
		}
	}

	public function onController(){
		for($page=1;$page<=$this->pageCount;$page++){
			$this->openPDF($page);
			//if($page == 3){break;}
		}
		$this->PDF->close();
		$this->readFiles();
	}
}
	$file = "APRIL2015_1.pdf";
	//$file = "acupuncture_voucher.pdf";
	//$file = "test.jpg";
	//$OCR = new \Smalot\PdfParser\Parser();
	$P =  new parse($file);

?>