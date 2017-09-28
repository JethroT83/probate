<?php
namespace app\providers{
	use \app\services\parseService as service;
	use \app\services\runService as run;
	use \app\services\cacheService as cache;
	Class A_parseDocket extends \app\textParser{


		public function __construct($text){
			$this->text = $text;
			$this->onController();
			#return $this->result;
		}
		
		#########################################################
		################	PARSING FUNCTIONS 	 ################
		#########################################################


		# LEVEL 1 #
		private function parseLevel1(){
			$lines = explode("\n",$this->text);

			foreach($lines as $i => $line){
				$_line = str_replace(' ', '', $line);

				if(stripos($_line, "DocketNumber:")!==false){
					$a = stripos($_line, "DocketNumber:") + 13;
					$docket = substr($_line,$a,6);

					return trim($docket);
				}
			}
		}
		
		# LEVEL 2 #
		private function parseLevel2(){
exec("sudo chmod 777 ".root." -R");
			//Delete it at current page
			#run::unlinkPage(cache::getCache("page"));

$imageFile 	= root."/storage/cache/build_p5.jpg";
$pdfFile 	= root."/storage/cache/build_p5.pdf";
@unlink($imageFile);
@unlink($pdfFile);
			//Convert JPG again, increasing the quality from 100 to 300
			run::convertToJPG($pdfFile, $imageFile , $density = 413, $quality = 300);

$txtFile 	= root."/storage/cache/build_p5.txt";
@unlink($txtFile);

			//Convert to TXT
			run::readJPG($imageFile);

			//Reparse
			return $this->parseLevel1();

		}



		#########################################################
		################	TESTING FUNCTIONS 	 ################
		#########################################################

		private function testLevel1($docket){
			$out 	= cache::getCache("out");
			$page 	= cache::getCache("page");

			if($docket > $out[$page-1]){
				return true;
			}else{
				return false;
			}

		} 


		
		public function onController(){
			$docket = $this->parseLevel1();
			$test = $this->testLevel1($docket);
			if($test == -1){
				$docket =  $this->parseLevel2();
			}

			$this->result = $docket;
		}
	}
}

		/*$string = "";
		for($x=0;$x<=strlen($this->result);$x++){
			if(is_numeric(substr($this->result,$x))){
				$string .= substr($this->result,$x,1);
				if(strlen($string) == 7){break;}
			}
		}
		return $string;*/
?>
