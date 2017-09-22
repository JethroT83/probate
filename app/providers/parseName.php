<?php
namespace app\providers{
class parseName extends \app\providers\textParser{
	public function __construct($result){
		$this->result = trim($result);
	}
	
	public function countSpaces(){
		$n = 0;
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result, $x);
			if(ord($l) ==32){
				$n++;
			}
		}
		return $n;
	}
	
	
	public function detectMiddleInitial(){
		$n = 0;
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result, $x,1);
			if(ord($l) ==46){
				$a = substr($this->result, $x-2,1);
				$b = substr($this->result, $x-1,1);
				$c = $l;
				$d = substr($this->result, $x+1,1);
				
				if(ord($a) == 32 && ord($d)==32){
					return 1;
				}
			}
		}
		return -1;
	}
	
	public function removeMiddleInitial(){
		$n = 0;
		for($x=0;$x<=strlen($this->result);$x++){
			$l = substr($this->result, $x,1);
			if(ord($l) ==46){
				$a = substr($this->result, $x-2,1);
				$b = substr($this->result, $x-1,1);
				$c = $l;
				$d = substr($this->result, $x+1,1);
				
				if(ord($a) == 32 && ord($d)==32){
					$initialText  = $a.$b.$c;
					return str_replace($initialText, "", $this->result);
				}
			}
		}
		return -1;
	}

}
}
?>