<?php
namespace app{
use \app\runService as service;
Class parse {

	private $pdfFiles = array();

	public function __construct($file){	
		

		$this->masterFile = $file;

		// Gets the actual file name
		$e = explode("/",$file);
		$this->fname = substr(end($e),0,-4);


		$this->PDF = new \fpdi\FPDI();
		$this->pageCount = $this->PDF->setSourceFile($file);

		$this->onController();
	}


	public function paginatePDF($page){
		$PDF = new \fpdi\FPDI();
		$PDF->setSourceFile($this->masterFile);
		$PDF->AddPage();
		$PDF->setSourceFile($this->masterFile);
		$tplIdx = $this->PDF->importPage($page);
		$PDF->useTemplate($tplIdx);
		$f = $this->pdfFiles[count($this->pdfFiles)] = __DIR__."/../storage/cache/{$this->fname}_p{$page}.pdf";
		$this->PDF->output($f,'F');
		return $f;
	}
	


	public function parse($text){
		$P = new textParser($text, $this->zip);
		$P::setName($this->getName());
		$P->parseText();
		return $P->out;
	}

	public function onController(){

		# Get the Number of Pages
		$PDF = new \fpdi\FPDI();
		$pageCount = $PDF->setSourceFile($file);


		exec("sudo chmod 777 storage -R");
echo "\n\nThis is page count -->". $this->pageCount;
		for($page=1;$page<=$pageCount;$page++){
echo "\n".$page;
			$textFile = __DIR__."/../storage/cache/{$this->fname}_p{$page}.txt";

			if(is_file($textFile)){
				$text = file_get_contents($textFile);
			}else{
				#echo "<h2 style='color:red'>$page</h2>";
				$pdfFile = $this->paginatePDF($page);
				$imageFile = __DIR__."/../storage/cache/{$this->fname}_p{$page}.jpg";

				#echo "<h2 style='color:red'>This is the imageFile-->$imageFile</h2>";
				service::convertToJPG($pdfFile, $imageFile);
				$text = service::readJPG($imageFile);

				file_put_contents($textFile, $text);
			}
			
			exec("sudo chmod 777 storage -R");
			$out[$page] = $this->parse($text);
		}
		exec("sudo chmod 777 storage -R");
		$this->array_to_CSV($out,  substr($this->masterFile,0,-4); . "_out" );
	}
}
}

