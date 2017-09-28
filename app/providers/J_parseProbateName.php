<?php
namespace app\providers{
use \app\parseService as service;
use \app\providers\parseName as parseName;
Class J_parseProbateName extends \app\textParser{
	
	public function __construct($text, $stop = "Exe"){
		$this->text = $text;
		$this->stop = $stop;
		$this->onController();
	}
	

	public function parseLevel1(){
		$lines = explode("\n",$this->text);

		$l = "";
		$search = false;
		foreach($lines as $i => $line){

			if(strpos($line,"Relation") !== false){
				$search = true;
				continue;
			}

			if($search === true){
				$name = parseName::findName($line);
				if($name !== false){
					$l = $line;
					break;
				}
			}
		}

		$p = strpos($l, "am");

		return trim(substr($line,0,$p));

	}
	
	
	public function onController(){
		$this->result = $this->parseLevel1();
	}
}
}
?>