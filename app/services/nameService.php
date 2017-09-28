<?php
namespace app\services{
	class nameService{
		public function __construct($result){
			$this->result = trim($result);
		}
		

		# Sets Name
		private static function setName(){

			$get = "SELECT * FROM probate.name";
			$data = $GLOBALS['connection']->select($get);

			$result = array();
			foreach($data as $i => $info){
				$result[$info['id']] = $info['name'];
			}

			self::$name = $result;
		}


		# Returns a list of 6800 most common names
		public static function getName(){

			if(count(self::$name) == 0){self::setName();}

			return self::$name;
		}




		public static function findName($line){
			foreach(self::$name as $id => $name){

				$r = self::compare($line,$name);

				if($r !== false){
					return $name;
				}
			}

			return false;
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