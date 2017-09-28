<?php
namespace app\providers{
use \app\parseService as service;
class parseDate extends \app\textParser{
	public function __construct($result){
		$this->result = $result;
	}
	
	#This eliminates spaces in string
	public function testSpace(){
		$string = "";
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result, $x);
			
			if(ord($l) ==32){
				return -1;
			}
		}
		return 1;
	
	}
	
	#This is eliminate all the spaces
	public function parseNoSpace(){
		$string = "";
		for($x=0;$x<=10;$x++){
			$l = substr($this->result, $x,1);
			
			if(ord($l) !=32){
				$string .= $l;
			}
		}
		return $string;
	}
	
	public function findEnd($result){
		for($x=strlen($result);$x>0;$x--){
			$l = substr($result, $x);
			if(ord($l) ==47){
				return substr($result, 0,$x+4);
			}
		}
	}

}
}