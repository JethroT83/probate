<?php

namespace app{
Class textParser{

	public function __construct($text, $ZIP){
		$this->zip = $ZIP;
		$this->text = $text;
	}

	public function nextWord($text,$pos){
		for($x=$pos;$x<=strlen($text);$x++){
			$c = substr($text,$x);
			if(ord($c)==32){
				return $x;
			}
		}
	
	}
	
	function match($string1, $string2, $threshold=.95){
		if(strlen($string1)>0 and strlen($string2)>0){
			$num = levenshtein($string1,$string2);
			$length = strlen($string1)<strlen($string2) ? strlen($string1) : strlen($string2);
			$match = 1- round(floatval($num/$length),2);
			if($match<$threshold and $threshold!= -1){
				return -1;
			}else if($threshold==-1){
				return $match;
			}else{
				return 1;
			}
		}else{
			return -1;
		}
	}
	
	function closeMatch($string ,$array){
		$string = strtolower($string);
		foreach($array as $key => $value){
				$value = strtolower($value);
				if( levenshtein($string, $value) <= 1){
					return $string;
				}
		}
		return -1;
	}
	
	function strposProbate($haystack,$string){
		$string = strtolower($string);
		$words = explode(" ", $haystack);
		$j = 0;
		$test = 0;
		$last = 0;
		foreach($words as $i => $word){
			$word = trim(strtolower($word));
			if($j == 0){
				$count = strlen($word);
				$j++;
			}else{
				$count = $count  + strlen($word) + 1;
			}		
			//echo "<br>COUNT-- $count  STRING--$string --- $word";
			if(levenshtein("type", $word)<= 1){$test++; $last = $i;}
			if(levenshtein("address:", $word)<= 1){$test++;$last = $i;}
			if(levenshtein("status" , $word)<= 1){$test++;$last = $i;}
				
			if($test >2){	
				return $count +1;
			}
			
			if($last < $i-3){
				$test = 0;
			}
		}
		return -1;
	}
	
	function proper($string){
		$string = strtolower($string);
		$cap  =  strtoupper(substr($string, 0, 1));
		$word = substr($string,1,strlen($string));
		return $cap . $word;
	}
	
	function lowerCase($string){
		$string = "";
		for($x=0;strlen($string);$x++){
			$l = substr($string,$x,1);
			if(ord($l)>=65 && ord($l)<=90){
				$string .= char(ord($l)+32);
			}else{
				$string .= $l;
			}
		}
		return $string;
	}
	
	public function detectNumber($string){
		for($x=0;$x<=strlen($string);$x++){
			$l = substr($string, $x);
			if(ord($l) >=48 and ord($l)<= 57){
				return $x;
			}
		}
		return -1;
	}
	
	public function explodeWord($string){
		$string  = trim($string);
		$words  = array();
		$word = "";
		for($x=0;$x<=strlen($string);$x++){
			$l = substr($string, $x,1);
			if(ord($l) == 32 || ord($l) == 160){
				$words[] = $word;
				$word = "";
			}else{
				$word .= $l;
			}
		}
		return $words;
	}
	

	public function parseProbate($text){
		$probateDate = new \app\providers\parseProbateDate($text);
		$deathDate = new \app\providers\parseDeathDate($text);
		$deceasedName = new \app\providers\parseDeceasedName($text);
		$deceasedAddress = new \app\providers\parseDeceasedAddress($text, $this->zip);
		$probateName = new \app\providers\parseProbateName($text);
		$probateAddress = new \app\providers\parseProbateAddress($text, $this->zip);
		
		$this->out['CaseType'] = $this->caseType;
		$this->out['ProbateDate'] = $probateDate->result;
		$this->out['DateofDeath'] = $deathDate->result;
		$this->out['DecdFullNamePulled'] = $deceasedName->result;
		$this->out['DecdLastAddress'] = $deceasedAddress->result['street'];
		$this->out['DecdLastCity'] = $deceasedAddress->result['city'];
		$this->out['DecdLastState'] = $deceasedAddress->result['state'];
		$this->out['DecdLastZip'] = $deceasedAddress->result['zip'];
		$this->out['PRFullNamePulled'] = $probateName->result;
		$this->out['PRAddress'] = $probateAddress->result['street'];
		$this->out['PRCity'] = $probateAddress->result['city'];
		$this->out['PRState'] = $probateAddress->result['state'];
		$this->out['PRZip'] = $probateAddress->result['zip'];
		
	}
	
	public function parseNextofKin($text){
		$probateDate = new \app\providers\parseProbateDate($text);
		$deathDate = new \app\providers\parseDeathDate($text);
		$deceasedName = new \app\providers\parseDeceasedName($text);
		$deceasedAddress = new \app\providers\parseDeceasedAddress($text, $this->zip);
		$probateName = new \app\providers\parseProbateName($text, "Af");	
		$probateAddress = new \app\providers\parseProbateAddress($text, $this->zip);
		
		/*echo "<br><H1>ProbateDate:" . $probateDate->result . "</H1>";
		echo "<br><H1>DeathDate:" . $deathDate->result . "</H1>";
		echo "<br><H1>DeceasedName:" . $deceasedName->result . "</H1>";
		
		$array = $deceasedAddress->result;
		$deceasedString = "";
		foreach($array as $i => $info){
			$deceasedString .= $info;
		}
		echo "<br><H1>DeceasedAddress" . $deceasedString. "</H1>";
		
		echo "<br><H1>ProbateName: " . $probateName->result . "</H1>";
		
		$array = $probateAddress->result;
		$probateString = "";
		foreach($array as $i => $info){
			$probateString .= $info;
		}
		echo "<br><H1>ProbateAddress" . $probateString . "</H1>";*/
		$this->out['CaseType'] = $this->caseType;
		$this->out['ProbateDate'] = $probateDate->result;
		$this->out['DateofDeath'] = $deathDate->result;
		$this->out['DecdFullNamePulled'] = $deceasedName->result;
		$this->out['DecdLastAddress'] = $deceasedAddress->result['street'];
		$this->out['DecdLastCity'] = $deceasedAddress->result['city'];
		$this->out['DecdLastState'] = $deceasedAddress->result['state'];
		$this->out['DecdLastZip'] = $deceasedAddress->result['zip'];
		$this->out['PRFullNamePulled'] = $probateName->result;
		$this->out['PRAddress'] = $probateAddress->result['street'];
		$this->out['PRCity'] = $probateAddress->result['city'];
		$this->out['PRState'] = $probateAddress->result['state'];
		$this->out['PRZip'] = $probateAddress->result['zip'];
		
		
		
	}
	
	public function parseText(){
		$text = $this->text;
		$docket = new \app\providers\parseDocket($text);
				
		$caseType = new \app\providers\parseCaseType($text);
			
		#echo "<br><H1>Docket:" . $docket->result . "</H1>";
		#echo "<br><H1>CaseType:" . $caseType->result . "</H1>";	
		
		$this->out['ProbateType'] = "val";
		$this->out['Docket'] = $docket->result;
		
		switch($caseType->result){
			case "PROBATE":
				$this->caseType = "PROBATE";
				$this->parseProbate($text);
				break;
			case "NEXT OF KIN AFFIDAVIT":
				$this->caseType = "NEXT OF KIN AFFIDAVIT";
				$this->parseNextofKin($text);
				break;
			case "SPOUSE AFFIDAVIT":
				$this->caseType = "SPOUSE AFFIDAVIT";
				$this->parseNextofKin($text);
				break;
		}	
	}
}
}